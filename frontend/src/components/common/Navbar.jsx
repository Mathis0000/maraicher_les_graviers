import { useContext } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { AuthContext } from '../../context/AuthContext';
import { CartContext } from '../../context/CartContext';

const Navbar = () => {
  const { user, logout, isAdmin } = useContext(AuthContext);
  const { getCartItemsCount } = useContext(CartContext);
  const navigate = useNavigate();

  const handleLogout = () => {
    logout();
    navigate('/login');
  };

  return (
    <nav className="navbar">
      <div className="navbar-container">
        <Link to="/" className="navbar-brand">
          <h1>🥕 Maraîcher</h1>
        </Link>
        <ul className="navbar-menu">
          <li><Link to="/products">Produits</Link></li>
          <li><Link to="/contact">Contact</Link></li>
          {user && (
            <>
              <li>
                <Link to="/cart">
                  Panier ({getCartItemsCount()})
                </Link>
              </li>
              <li><Link to="/orders">Mes commandes</Link></li>
              {isAdmin && (
                <li><Link to="/admin">Admin</Link></li>
              )}
            </>
          )}
        </ul>
        <div className="navbar-actions">
          {user ? (
            <>
              <span className="user-name">Bonjour, {user.firstName}</span>
              <button onClick={handleLogout} className="btn btn-secondary">
                Déconnexion
              </button>
            </>
          ) : (
            <>
              <Link to="/login" className="btn btn-secondary">
                Connexion
              </Link>
              <Link to="/register" className="btn btn-primary">
                Inscription
              </Link>
            </>
          )}
        </div>
      </div>
    </nav>
  );
};

export default Navbar;
