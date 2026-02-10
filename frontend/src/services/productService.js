import api from './api';

export const getProducts = async (category = null) => {
  const params = category ? { category } : {};
  const response = await api.get('/products', { params });
  return response.data.products;
};

export const getProductById = async (id) => {
  const response = await api.get(`/products/${id}`);
  return response.data;
};

export const createProduct = async (productData) => {
  const response = await api.post('/products', productData);
  return response.data.product;
};

export const updateProduct = async (id, productData) => {
  const response = await api.put(`/products/${id}`, productData);
  return response.data.product;
};

export const deleteProduct = async (id) => {
  const response = await api.delete(`/products/${id}`);
  return response.data;
};

export const uploadProductImage = async (id, imageFile) => {
  const formData = new FormData();
  formData.append('image', imageFile);

  const response = await api.post(`/products/${id}/image`, formData, {
    headers: {
      'Content-Type': 'multipart/form-data',
    },
  });
  return response.data;
};

export const getAllProductsAdmin = async () => {
  const response = await api.get('/products/admin/all');
  return response.data.products;
};
