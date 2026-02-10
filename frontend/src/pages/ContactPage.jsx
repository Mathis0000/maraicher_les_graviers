import heroImage from '../assets/hero.jpg';

const ContactPage = () => {
  return (
    <div className="contact-page">
      <section className="hero hero-home" style={{ '--hero-bg': `url(${heroImage})` }}>
        <h1>Nous trouver</h1>
        <p>Retrouvez-nous chaque semaine a ces trois endroits</p>
      </section>
      <section className="features">
        <div className="feature">
          <h3>Marche d'Issoire</h3>
          <p>Le samedi matin</p>
          <div className="map-embed">
            <iframe
              title="Marche d'Issoire - Place de la Republique"
              src="https://www.google.com/maps?q=March%C3%A9%20Issoire%2C%20Pl.%20de%20la%20R%C3%A9publique%2C%2063500%20Issoire&output=embed"
              width="100%"
              height="240"
              style={{ border: 0 }}
              loading="lazy"
              referrerPolicy="no-referrer-when-downgrade"
            />
          </div>
        </div>
        <div className="feature">
          <h3>Magasin la Paysanerie</h3>
          <p>Produits disponibles en boutique</p>
          <div className="map-embed">
            <iframe
              title="La Paysanne Rit - Issoire"
              src="https://www.google.com/maps?q=La%20Paysanne%20Rit%2C%208%20Rue%20Henri%20Barbusse%2C%2063500%20Issoire&output=embed"
              width="100%"
              height="240"
              style={{ border: 0 }}
              loading="lazy"
              referrerPolicy="no-referrer-when-downgrade"
            />
          </div>
        </div>
        <div className="feature">
          <h3>Au Jardin</h3>
          <p>Jardinier Maraicher Les Graviers, Les Graviers, 63500 Le Broc</p>
          <div className="map-embed">
            <iframe
              title="Jardinier Maraicher Les Graviers - Le Broc"
              src="https://www.google.com/maps?q=Jardinier%20Mara%C3%AEcher%20Les%20Graviers%2C%20Les%20Graviers%2C%2063500%20Le%20Broc&output=embed"
              width="100%"
              height="240"
              style={{ border: 0 }}
              loading="lazy"
              referrerPolicy="no-referrer-when-downgrade"
            />
          </div>
        </div>
      </section>
    </div>
  );
};

export default ContactPage;
