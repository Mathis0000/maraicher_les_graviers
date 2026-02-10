import { useContext } from 'react';
import { Link } from 'react-router-dom';
import { CartContext } from '../../context/CartContext';

const Cart = () => {
  const { cart, updateQuantity, removeFromCart, getCartTotal } = useContext(CartContext);

  if (cart.length === 0) {
    return (
      <div className="cart-empty">
        <h2>Votre panier est vide</h2>
        <Link to="/products" className="btn btn-primary">
          Voir les produits
        </Link>
      </div>
    );
  }

  return (
    <div className="cart">
      <h2>Votre panier</h2>
      <div className="cart-items">
        {cart.map((item) => (
          <div key={item.id} className="cart-item">
            <div className="cart-item-info">
              <h3>{item.name}</h3>
              <p>{parseFloat(item.price).toFixed(2)} €/{item.unit}</p>
            </div>
            <div className="cart-item-actions">
              <button
                onClick={() => updateQuantity(item.id, item.quantity - 1)}
                className="btn btn-sm"
              >
                -
              </button>
              <span className="cart-item-quantity">{item.quantity}</span>
              <button
                onClick={() => updateQuantity(item.id, item.quantity + 1)}
                className="btn btn-sm"
              >
                +
              </button>
              <button
                onClick={() => removeFromCart(item.id)}
                className="btn btn-danger btn-sm"
              >
                Supprimer
              </button>
            </div>
            <div className="cart-item-subtotal">
              {(parseFloat(item.price) * item.quantity).toFixed(2)} €
            </div>
          </div>
        ))}
      </div>
      <div className="cart-summary">
        <h3>Total: {getCartTotal().toFixed(2)} €</h3>
        <Link to="/checkout" className="btn btn-primary">
          Passer la commande
        </Link>
      </div>
    </div>
  );
};

export default Cart;
