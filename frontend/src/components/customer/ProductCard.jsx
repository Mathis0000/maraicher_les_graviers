import { useContext } from 'react';
import { useNavigate } from 'react-router-dom';
import { CartContext } from '../../context/CartContext';
import { AuthContext } from '../../context/AuthContext';
import { useToast } from '../../context/ToastContext';

const ProductCard = ({ product }) => {
  const { addToCart } = useContext(CartContext);
  const { isAuthenticated } = useContext(AuthContext);
  const { showToast } = useToast();
  const navigate = useNavigate();
  const apiUrl = import.meta.env.VITE_API_URL.replace('/api', '');

  const handleAddToCart = () => {
    if (!isAuthenticated) {
      showToast('Vous devez être connecté pour ajouter des produits au panier', 'error');
      navigate('/login');
      return;
    }
    addToCart(product, 1);
    showToast('Produit ajouté au panier !', 'success');
  };

  return (
    <div className="product-card">
      <div className="product-image">
        {product.image_url ? (
          <img src={`${apiUrl}${product.image_url}`} alt={product.name} />
        ) : (
          <div className="product-image-placeholder">📦</div>
        )}
      </div>
      <div className="product-info">
        <h3>{product.name}</h3>
        <p className="product-description">{product.description}</p>
        <div className="product-details">
          <span className="product-price">{parseFloat(product.price).toFixed(2)} €/{product.unit}</span>
          <span className="product-stock">
            {product.stock_quantity > 0 ? `En stock: ${product.stock_quantity}` : 'Rupture'}
          </span>
        </div>
        {product.stock_quantity > 0 && (
          <button onClick={handleAddToCart} className="btn btn-primary">
            Ajouter au panier
          </button>
        )}
      </div>
    </div>
  );
};

export default ProductCard;