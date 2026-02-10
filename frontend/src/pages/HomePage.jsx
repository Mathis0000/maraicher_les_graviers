import { Link } from 'react-router-dom';

const HomePage = () => {
  return (
    <div className="home-page">
      <section className="hero">
        <h1>Maraîcher les graviers</h1>
        <p>Des produits frais et locaux, cultivés avec passion</p>
        <Link to="/products" className="btn btn-primary btn-lg">
          Découvrir nos produits
        </Link>
      </section>
      <section className="features">
        <div className="feature">
          <h3>🌱 Produits frais</h3>
          <p>Directement du producteur</p>
        </div>
        <div className="feature">
          <h3>🚚 Livraison locale</h3>
          <p>Paiement à la livraison</p>
        </div>
        <div className="feature">
          <h3>🌍 Agriculture locale</h3>
          <p>Soutenez les producteurs locaux</p>
        </div>
      </section>
    </div>
  );
};

export default HomePage;
