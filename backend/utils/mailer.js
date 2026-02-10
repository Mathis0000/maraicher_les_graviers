const sgMail = require('@sendgrid/mail');

const getMailerConfig = () => {
  const apiKey = process.env.SENDGRID_API_KEY;
  const fromEmail = process.env.SENDGRID_FROM_EMAIL;
  const appBaseUrl = process.env.FRONTEND_URL || 'http://localhost:3000';

  return {
    apiKey,
    fromEmail,
    appBaseUrl
  };
};

const isConfigured = () => {
  const { apiKey, fromEmail } = getMailerConfig();
  return Boolean(apiKey && fromEmail);
};

const sendPasswordResetEmail = async ({ to, resetUrl }) => {
  if (!isConfigured()) {
    return false;
  }

  const { apiKey, fromEmail } = getMailerConfig();
  sgMail.setApiKey(apiKey);

  const subject = 'Reinitialisation du mot de passe';
  const text = [
    'Bonjour,',
    '',
    'Vous avez demande la reinitialisation de votre mot de passe.',
    `Lien de reinitialisation : ${resetUrl}`,
    '',
    'Si vous n\'etes pas a l\'origine de cette demande, ignorez cet email.'
  ].join('\n');

  const html = `
    <p>Bonjour,</p>
    <p>Vous avez demande la reinitialisation de votre mot de passe.</p>
    <p><a href="${resetUrl}">Reinitialiser mon mot de passe</a></p>
    <p>Si vous n'etes pas a l'origine de cette demande, ignorez cet email.</p>
  `;

  try {
    await sgMail.send({
      to,
      from: fromEmail,
      subject,
      text,
      html
    });
    return true;
  } catch (error) {
    console.error('SendGrid error:', error?.response?.body || error?.message || error);
    return false;
  }
};

module.exports = {
  sendPasswordResetEmail
};
