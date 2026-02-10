import { useState, useContext } from 'react';
import { useNavigate } from 'react-router-dom';
import { CartContext } from '../../context/CartContext';
import { AuthContext } from '../../context/AuthContext';
import * as orderService from '../../services/orderService';

const Checkout = () => {
  const { cart, getCartTotal, clearCart } = useContext(CartContext);
  const { user } = useContext(AuthContext);
  const navigate = useNavigate();
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  const [formData, setFormData] = useState({
    deliveryAddress: user?.address || '',
    deliveryCity: user?.city || '',
    deliveryPostalCode: user?.postalCode || '',
    deliveryPhone: user?.phone || '',
    notes: ''
  });

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');

    try {
      const items = cart.map(item => ({
        productId: item.id,
        quantity: item.quantity
      }));

      await orderService.createOrder({
        items,
        ...formData
      });

      clearCart();
      alert('Commande créée avec succès !');
      navigate('/orders');
    } catch (err) {
      setError(err.response?.data?.error || 'Erreur lors de la commande');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="checkout">
      <h2>Finaliser la commande</h2>
      {error && <div className="error-message">{error}</div>}

      <div className="checkout-content">
        <div className="checkout-form">
          <h3>Informations de livraison</h3>
          <form onSubmit={handleSubmit}>
            <div className="form-group">
              <label htmlFor="deliveryAddress">Adresse *</label>
              <input
                type="text"
                id="deliveryAddress"
                name="deliveryAddress"
                value={formData.deliveryAddress}
                onChange={handleChange}
                required
              />
            </div>
            <div className="form-row">
              <div className="form-group">
                <label htmlFor="deliveryCity">Ville *</label>
                <input
                  type="text"
                  id="deliveryCity"
                  name="deliveryCity"
                  value={formData.deliveryCity}
                  onChange={handleChange}
                  required
                />
              </div>
              <div className="form-group">
                <label htmlFor="deliveryPostalCode">Code postal *</label>
                <input
                  type="text"
                  id="deliveryPostalCode"
                  name="deliveryPostalCode"
                  value={formData.deliveryPostalCode}
                  onChange={handleChange}
                  required
                />
              </div>
            </div>
            <div className="form-group">
              <label htmlFor="deliveryPhone">Téléphone *</label>
              <input
                type="tel"
                id="deliveryPhone"
                name="deliveryPhone"
                value={formData.deliveryPhone}
                onChange={handleChange}
                required
              />
            </div>
            <div className="form-group">
              <label htmlFor="notes">Notes (optionnel)</label>
              <textarea
                id="notes"
                name="notes"
                value={formData.notes}
                onChange={handleChange}
                rows="3"
              />
            </div>
            <button type="submit" className="btn btn-primary" disabled={loading}>
              {loading ? 'Validation...' : 'Valider la commande'}
            </button>
          </form>
        </div>

        <div className="checkout-summary">
          <h3>Récapitulatif</h3>
          {cart.map((item) => (
            <div key={item.id} className="summary-item">
              <span>{item.name} x {item.quantity}</span>
              <span>{(parseFloat(item.price) * item.quantity).toFixed(2)} €</span>
            </div>
          ))}
          <div className="summary-total">
            <strong>Total:</strong>
            <strong>{getCartTotal().toFixed(2)} €</strong>
          </div>
          <p className="payment-info">Paiement à la livraison</p>
        </div>
      </div>
    </div>
  );
};

export default Checkout;
