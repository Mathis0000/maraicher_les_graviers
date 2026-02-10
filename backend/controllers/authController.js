const crypto = require('crypto');
const User = require('../models/User');
const PasswordResetToken = require('../models/PasswordResetToken');
const { comparePassword } = require('../utils/passwordHelper');
const { generateToken } = require('../utils/jwtHelper');
const { sendPasswordResetEmail } = require('../utils/mailer');
const { validationResult } = require('express-validator');

const register = async (req, res, next) => {
  try {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
      return res.status(400).json({ errors: errors.array() });
    }

    const { email, password, firstName, lastName, phone, address, city, postalCode } = req.body;

    const existingUser = await User.findByEmail(email);
    if (existingUser) {
      return res.status(409).json({ error: 'Email already registered' });
    }

    const user = await User.create({
      email,
      password,
      firstName,
      lastName,
      phone,
      address,
      city,
      postalCode,
      role: 'customer'
    });

    const token = generateToken(user.id, user.role);

    res.status(201).json({
      message: 'User registered successfully',
      token,
      user: {
        id: user.id,
        email: user.email,
        firstName: user.first_name,
        lastName: user.last_name,
        role: user.role
      }
    });
  } catch (error) {
    next(error);
  }
};

const login = async (req, res, next) => {
  try {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
      return res.status(400).json({ errors: errors.array() });
    }

    const { email, password } = req.body;

    const user = await User.findByEmail(email);
    if (!user) {
      return res.status(401).json({ error: 'Invalid email or password' });
    }

    const isPasswordValid = await comparePassword(password, user.password);
    if (!isPasswordValid) {
      return res.status(401).json({ error: 'Invalid email or password' });
    }

    const token = generateToken(user.id, user.role);

    res.json({
      message: 'Login successful',
      token,
      user: {
        id: user.id,
        email: user.email,
        firstName: user.first_name,
        lastName: user.last_name,
        role: user.role
      }
    });
  } catch (error) {
    next(error);
  }
};

const getProfile = async (req, res, next) => {
  try {
    const user = await User.findById(req.user.id);

    if (!user) {
      return res.status(404).json({ error: 'User not found' });
    }

    res.json({
      id: user.id,
      email: user.email,
      firstName: user.first_name,
      lastName: user.last_name,
      phone: user.phone,
      address: user.address,
      city: user.city,
      postalCode: user.postal_code,
      role: user.role,
      createdAt: user.created_at
    });
  } catch (error) {
    next(error);
  }
};

const updateProfile = async (req, res, next) => {
  try {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
      return res.status(400).json({ errors: errors.array() });
    }

    const { firstName, lastName, phone, address, city, postalCode } = req.body;

    const user = await User.update(req.user.id, {
      firstName,
      lastName,
      phone,
      address,
      city,
      postalCode
    });

    res.json({
      message: 'Profile updated successfully',
      user: {
        id: user.id,
        email: user.email,
        firstName: user.first_name,
        lastName: user.last_name,
        phone: user.phone,
        address: user.address,
        city: user.city,
        postalCode: user.postal_code,
        role: user.role
      }
    });
  } catch (error) {
    next(error);
  }
};

const buildResetResponse = (token, expiresAt) => {
  if (process.env.NODE_ENV === 'production') {
    return { message: "Si l'email existe, un lien de reinitialisation a ete envoye." };
  }

  return {
    message: 'Reset token generated (dev only).',
    resetToken: token,
    resetTokenExpiresAt: expiresAt
  };
};

const requestPasswordReset = async (req, res, next) => {
  try {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
      return res.status(400).json({ errors: errors.array() });
    }

    const { email } = req.body;
    const user = await User.findByEmail(email);

    if (!user) {
      return res.json({ message: "Si l'email existe, un lien de reinitialisation a ete envoye." });
    }

    const rawToken = crypto.randomBytes(32).toString('hex');
    const tokenHash = crypto.createHash('sha256').update(rawToken).digest('hex');
    const expiresAt = new Date(Date.now() + 1000 * 60 * 60);

    await PasswordResetToken.create({
      userId: user.id,
      tokenHash,
      expiresAt
    });

    const appBaseUrl = process.env.FRONTEND_URL || 'http://localhost:3000';
    const resetUrl = `${appBaseUrl}/reset-password/${rawToken}`;
    const emailSent = await sendPasswordResetEmail({
      to: user.email,
      resetUrl
    });

    if (!emailSent && process.env.NODE_ENV !== 'development') {
      return res.json({ message: "Si l'email existe, un lien de reinitialisation a ete envoye." });
    }

    res.json(buildResetResponse(rawToken, expiresAt));
  } catch (error) {
    next(error);
  }
};

const resetPassword = async (req, res, next) => {
  try {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
      return res.status(400).json({ errors: errors.array() });
    }

    const { token, password } = req.body;
    const tokenHash = crypto.createHash('sha256').update(token).digest('hex');

    const resetRecord = await PasswordResetToken.findValidByTokenHash(tokenHash);
    if (!resetRecord) {
      return res.status(400).json({ error: 'Invalid or expired reset token' });
    }

    await User.updatePassword(resetRecord.user_id, password);
    await PasswordResetToken.markUsed(resetRecord.id);

    res.json({ message: 'Password reset successfully' });
  } catch (error) {
    next(error);
  }
};

module.exports = {
  register,
  login,
  getProfile,
  updateProfile,
  requestPasswordReset,
  resetPassword
};
