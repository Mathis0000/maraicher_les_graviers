import { useState, useEffect } from 'react';
import ProductList from '../components/customer/ProductList';
import * as productService from '../services/productService';

const ProductsPage = () => {
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchProducts = async () => {
      try {
        const data = await productService.getProducts();
        setProducts(data);
      } catch (error) {
        console.error('Error fetching products:', error);
      } finally {
        setLoading(false);
      }
    };
    fetchProducts();
  }, []);

  if (loading) return <div className="loading-container">Chargement...</div>;

  return (
    <div className="products-page">
      <h1>Nos produits</h1>
      <ProductList products={products} />
    </div>
  );
};

export default ProductsPage;
