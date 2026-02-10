import { useState } from 'react';
import { Link } from 'react-router-dom';
import { requestPasswordReset } from '../../services/authService';

const ForgotPassword = () => {
  const [email, setEmail] = useState('');
  const [error, setError] = useState('');
  const [successMessage, setSuccessMessage] = useState('');
  const [resetToken, setResetToken] = useState('');
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');
    setSuccessMessage('');
    setResetToken('');

    try {
      const response = await requestPasswordReset(email);
      setSuccessMessage(response.message || 'Si un compte existe, un email a ete envoye.');
      if (response.resetToken) {
        setResetToken(response.resetToken);
      }
    } catch (err) {
      setError(err.response?.data?.error || 'Erreur lors de la demande de reinitialisation');
    } finally {
      setLoading(false);
    }
  };

  const resetLink = resetToken ? `${window.location.origin}/reset-password/${resetToken}` : '';

  return (
    <div className="auth-container">
      <div className="auth-card">
        <h2>Mot de passe oublie</h2>
        {error && <div className="error-message">{error}</div>}
        {successMessage && <div className="success-message">{successMessage}</div>}
        <form onSubmit={handleSubmit}>
          <div className="form-group">
            <label htmlFor="email">Email</label>
            <input
              type="email"
              id="email"
              name="email"
              autoComplete="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
            />
          </div>
          <button type="submit" className="btn btn-primary" disabled={loading}>
            {loading ? 'Envoi...' : 'Envoyer le lien'}
          </button>
        </form>
        {resetLink && (
          <p className="auth-link">
            Lien de reinitialisation (dev) : <Link to={`/reset-password/${resetToken}`}>Reinitialiser</Link>
          </p>
        )}
        <p className="auth-link">
          Vous avez deja un compte ? <Link to="/login">Se connecter</Link>
        </p>
      </div>
    </div>
  );
};

export default ForgotPassword;
