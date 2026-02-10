import ProductCard from './ProductCard';

const ProductList = ({ products }) => {
  if (products.length === 0) {
    return <p className="no-products">Aucun produit disponible</p>;
  }

  return (
    <div className="product-list">
      {products.map((product) => (
        <ProductCard key={product.id} product={product} />
      ))}
    </div>
  );
};

export default ProductList;
