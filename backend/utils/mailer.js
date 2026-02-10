const nodemailer = require('nodemailer');

const getMailerConfig = () => ({
  host: process.env.BREVO_SMTP_HOST || 'smtp-relay.brevo.com',
  port: Number(process.env.BREVO_SMTP_PORT || 587),
  user: process.env.BREVO_SMTP_USER,
  pass: process.env.BREVO_SMTP_PASS,
  fromEmail: process.env.BREVO_FROM_EMAIL,
  appBaseUrl: process.env.FRONTEND_URL || 'http://localhost:3000'
});

const isConfigured = () => {
  const { user, pass, fromEmail } = getMailerConfig();
  return Boolean(user && pass && fromEmail);
};

const sendPasswordResetEmail = async ({ to, resetUrl }) => {
  if (!isConfigured()) {
    return false;
  }

  const { host, port, user, pass, fromEmail } = getMailerConfig();
  const transporter = nodemailer.createTransport({
    host,
    port,
    secure: false,
    auth: { user, pass }
  });

  const subject = 'Reinitialisation du mot de passe';
  const text = [
    'Bonjour,',
    '',
    'Vous avez demande la reinitialisation de votre mot de passe.',
    `Lien de reinitialisation : ${resetUrl}`,
    '',
    "Si vous n'etes pas a l'origine de cette demande, ignorez cet email."
  ].join('\n');

  const html = `
    <p>Bonjour,</p>
    <p>Vous avez demande la reinitialisation de votre mot de passe.</p>
    <p><a href="${resetUrl}">Reinitialiser mon mot de passe</a></p>
    <p>Si vous n'etes pas a l'origine de cette demande, ignorez cet email.</p>
  `;

  try {
    await transporter.sendMail({
      to,
      from: fromEmail,
      subject,
      text,
      html
    });
    return true;
  } catch (error) {
    console.error('Brevo SMTP error:', error?.message || error);
    return false;
  }
};

module.exports = { sendPasswordResetEmail };
