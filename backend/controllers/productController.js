const Product = require('../models/Product');
const { validationResult } = require('express-validator');
const fs = require('fs');
const path = require('path');

const parseBool = (value, defaultValue) => {
  if (value === undefined) return defaultValue;
  if (typeof value === 'boolean') return value;
  return value === 'true';
};

const getAllProducts = async (req, res, next) => {
  try {
    const { category } = req.query;

    const filters = { isAvailable: true };
    if (category) {
      filters.category = category;
    }

    const products = await Product.findAll(filters);

    res.json({
      count: products.length,
      products
    });
  } catch (error) {
    next(error);
  }
};

const getAllProductsAdmin = async (req, res, next) => {
  try {
    const { category, isAvailable } = req.query;

    const filters = {};
    if (category) filters.category = category;
    if (isAvailable !== undefined) filters.isAvailable = isAvailable === 'true';

    const products = await Product.findAll(filters);

    res.json({
      count: products.length,
      products
    });
  } catch (error) {
    next(error);
  }
};

const getProductById = async (req, res, next) => {
  try {
    const { id } = req.params;

    const product = await Product.findById(id);

    if (!product) {
      return res.status(404).json({ error: 'Product not found' });
    }

    res.json(product);
  } catch (error) {
    next(error);
  }
};

const createProduct = async (req, res, next) => {
  try {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
      return res.status(400).json({ errors: errors.array() });
    }

    const { name, description, price, stockQuantity, unit, category, isAvailable } = req.body;

    const product = await Product.create({
      name,
      description,
      price: parseFloat(price),
      imageUrl: null,
      stockQuantity: parseInt(stockQuantity),
      unit,
      category,
      isAvailable: parseBool(isAvailable, true)
    });

    res.status(201).json({
      message: 'Product created successfully',
      product
    });
  } catch (error) {
    next(error);
  }
};

const updateProduct = async (req, res, next) => {
  try {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
      return res.status(400).json({ errors: errors.array() });
    }

    const { id } = req.params;
    const { name, description, price, stockQuantity, unit, category, isAvailable } = req.body;

    const existingProduct = await Product.findById(id);
    if (!existingProduct) {
      return res.status(404).json({ error: 'Product not found' });
    }

    const product = await Product.update(id, {
      name,
      description,
      price: price ? parseFloat(price) : undefined,
      stockQuantity: stockQuantity ? parseInt(stockQuantity) : undefined,
      unit,
      category,
      isAvailable: isAvailable !== undefined ? parseBool(isAvailable, undefined) : undefined
    });

    res.json({
      message: 'Product updated successfully',
      product
    });
  } catch (error) {
    next(error);
  }
};

const uploadProductImage = async (req, res, next) => {
  try {
    const { id } = req.params;

    if (!req.file) {
      return res.status(400).json({ error: 'No image file provided' });
    }

    const product = await Product.findById(id);
    if (!product) {
      fs.unlinkSync(req.file.path);
      return res.status(404).json({ error: 'Product not found' });
    }

    if (product.image_url) {
      const oldImagePath = path.join(__dirname, '..', product.image_url);
      if (fs.existsSync(oldImagePath)) {
        fs.unlinkSync(oldImagePath);
      }
    }

    const imageUrl = `/uploads/products/${req.file.filename}`;

    const updatedProduct = await Product.update(id, { imageUrl });

    res.json({
      message: 'Image uploaded successfully',
      imageUrl,
      product: updatedProduct
    });
  } catch (error) {
    if (req.file) {
      fs.unlinkSync(req.file.path);
    }
    next(error);
  }
};

const deleteProduct = async (req, res, next) => {
  try {
    const { id } = req.params;

    const product = await Product.findById(id);
    if (!product) {
      return res.status(404).json({ error: 'Product not found' });
    }

    if (product.image_url) {
      const imagePath = path.join(__dirname, '..', product.image_url);
      if (fs.existsSync(imagePath)) {
        fs.unlinkSync(imagePath);
      }
    }

    await Product.delete(id);

    res.json({
      message: 'Product deleted successfully'
    });
  } catch (error) {
    next(error);
  }
};

module.exports = {
  getAllProducts,
  getAllProductsAdmin,
  getProductById,
  createProduct,
  updateProduct,
  uploadProductImage,
  deleteProduct
};
