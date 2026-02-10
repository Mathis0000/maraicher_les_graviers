const express = require('express');
const router = express.Router();
const { body } = require('express-validator');
const productController = require('../controllers/productController');
const authenticate = require('../middleware/auth');
const checkRole = require('../middleware/roleCheck');
const upload = require('../config/multer');

router.get('/', productController.getAllProducts);

router.get('/admin/all', authenticate, checkRole('admin'), productController.getAllProductsAdmin);

router.get('/:id', productController.getProductById);

router.post(
  '/',
  authenticate,
  checkRole('admin'),
  [
    body('name').notEmpty().withMessage('Product name is required'),
    body('price').isFloat({ min: 0 }).withMessage('Price must be a positive number'),
    body('stockQuantity').isInt({ min: 0 }).withMessage('Stock quantity must be a positive integer'),
    body('unit').notEmpty().withMessage('Unit is required'),
  ],
  productController.createProduct
);

router.put(
  '/:id',
  authenticate,
  checkRole('admin'),
  [
    body('name').optional().notEmpty().withMessage('Product name cannot be empty'),
    body('price').optional().isFloat({ min: 0 }).withMessage('Price must be a positive number'),
    body('stockQuantity').optional().isInt({ min: 0 }).withMessage('Stock quantity must be a positive integer'),
  ],
  productController.updateProduct
);

router.post(
  '/:id/image',
  authenticate,
  checkRole('admin'),
  upload.single('image'),
  productController.uploadProductImage
);

router.delete('/:id', authenticate, checkRole('admin'), productController.deleteProduct);

module.exports = router;
