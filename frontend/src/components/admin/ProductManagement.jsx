import { useState, useEffect } from 'react';
import * as productService from '../../services/productService';

const ProductManagement = () => {
  const [products, setProducts] = useState([]);
  const [showForm, setShowForm] = useState(false);
  const [editingProduct, setEditingProduct] = useState(null);
  const [formData, setFormData] = useState({
    name: '',
    description: '',
    price: '',
    stockQuantity: '',
    unit: 'kg',
    category: '',
    isAvailable: true
  });
  const [imageFile, setImageFile] = useState(null);

  useEffect(() => {
    fetchProducts();
  }, []);

  const fetchProducts = async () => {
    try {
      const data = await productService.getAllProductsAdmin();
      setProducts(data);
    } catch (error) {
      console.error('Error fetching products:', error);
    }
  };

  const handleChange = (e) => {
    const value = e.target.type === 'checkbox' ? e.target.checked : e.target.value;
    setFormData({ ...formData, [e.target.name]: value });
  };

  const handleImageChange = (e) => {
    setImageFile(e.target.files[0]);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      let product;
      if (editingProduct) {
        product = await productService.updateProduct(editingProduct.id, formData);
      } else {
        product = await productService.createProduct(formData);
      }

      if (imageFile) {
        await productService.uploadProductImage(product.id, imageFile);
      }

      alert('Produit enregistré avec succès !');
      setShowForm(false);
      resetForm();
      fetchProducts();
    } catch (error) {
      alert('Erreur lors de l\'enregistrement du produit');
    }
  };

  const handleEdit = (product) => {
    setEditingProduct(product);
    setFormData({
      name: product.name,
      description: product.description,
      price: product.price,
      stockQuantity: product.stock_quantity,
      unit: product.unit,
      category: product.category,
      isAvailable: product.is_available
    });
    setShowForm(true);
  };

  const handleDelete = async (id) => {
    if (window.confirm('Supprimer ce produit ?')) {
      try {
        await productService.deleteProduct(id);
        fetchProducts();
      } catch (error) {
        alert('Erreur lors de la suppression');
      }
    }
  };

  const resetForm = () => {
    setEditingProduct(null);
    setFormData({
      name: '',
      description: '',
      price: '',
      stockQuantity: '',
      unit: 'kg',
      category: '',
      isAvailable: true
    });
    setImageFile(null);
  };

  return (
    <div className="product-management">
      <div className="management-header">
        <h2>Gestion des produits</h2>
        <button
          onClick={() => {
            resetForm();
            setShowForm(!showForm);
          }}
          className="btn btn-primary"
        >
          {showForm ? 'Annuler' : 'Ajouter un produit'}
        </button>
      </div>

      {showForm && (
        <form onSubmit={handleSubmit} className="product-form">
          <h3>{editingProduct ? 'Modifier' : 'Nouveau'} produit</h3>
          <div className="form-group">
            <label htmlFor="name">Nom *</label>
            <input
              type="text"
              id="name"
              name="name"
              value={formData.name}
              onChange={handleChange}
              required
            />
          </div>
          <div className="form-group">
            <label htmlFor="description">Description</label>
            <textarea
              id="description"
              name="description"
              value={formData.description}
              onChange={handleChange}
              rows="3"
            />
          </div>
          <div className="form-row">
            <div className="form-group">
              <label htmlFor="price">Prix *</label>
              <input
                type="number"
                step="0.01"
                id="price"
                name="price"
                value={formData.price}
                onChange={handleChange}
                required
              />
            </div>
            <div className="form-group">
              <label htmlFor="unit">Unité *</label>
              <select
                id="unit"
                name="unit"
                value={formData.unit}
                onChange={handleChange}
                required
              >
                <option value="kg">kg</option>
                <option value="pièce">pièce</option>
                <option value="barquette">barquette</option>
                <option value="botte">botte</option>
              </select>
            </div>
          </div>
          <div className="form-row">
            <div className="form-group">
              <label htmlFor="stockQuantity">Stock *</label>
              <input
                type="number"
                id="stockQuantity"
                name="stockQuantity"
                value={formData.stockQuantity}
                onChange={handleChange}
                required
              />
            </div>
            <div className="form-group">
              <label htmlFor="category">Catégorie</label>
              <input
                type="text"
                id="category"
                name="category"
                value={formData.category}
                onChange={handleChange}
              />
            </div>
          </div>
          <div className="form-group">
            <label htmlFor="image">Image</label>
            <input
              type="file"
              id="image"
              accept="image/*"
              onChange={handleImageChange}
            />
          </div>
          <div className="form-group">
            <label>
              <input
                type="checkbox"
                name="isAvailable"
                checked={formData.isAvailable}
                onChange={handleChange}
              />
              Disponible
            </label>
          </div>
          <button type="submit" className="btn btn-primary">
            Enregistrer
          </button>
        </form>
      )}

      <div className="products-table">
        <table>
          <thead>
            <tr>
              <th>Nom</th>
              <th>Prix</th>
              <th>Stock</th>
              <th>Catégorie</th>
              <th>Disponible</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            {products.map((product) => (
              <tr key={product.id}>
                <td>{product.name}</td>
                <td>{product.price} €/{product.unit}</td>
                <td>{product.stock_quantity}</td>
                <td>{product.category}</td>
                <td>{product.is_available ? 'Oui' : 'Non'}</td>
                <td>
                  <button onClick={() => handleEdit(product)} className="btn btn-sm">
                    Modifier
                  </button>
                  <button
                    onClick={() => handleDelete(product.id)}
                    className="btn btn-danger btn-sm"
                  >
                    Supprimer
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default ProductManagement;
