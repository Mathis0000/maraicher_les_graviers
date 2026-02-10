const express = require('express');
const router = express.Router();
const { body } = require('express-validator');
const orderController = require('../controllers/orderController');
const authenticate = require('../middleware/auth');
const checkRole = require('../middleware/roleCheck');

router.post(
  '/',
  authenticate,
  [
    body('items').isArray({ min: 1 }).withMessage('Order must contain at least one item'),
    body('items.*.productId').notEmpty().withMessage('Product ID is required'),
    body('items.*.quantity').isInt({ min: 1 }).withMessage('Quantity must be at least 1'),
    body('deliveryAddress').notEmpty().withMessage('Delivery address is required'),
    body('deliveryCity').notEmpty().withMessage('Delivery city is required'),
    body('deliveryPostalCode').notEmpty().withMessage('Delivery postal code is required'),
    body('deliveryPhone').notEmpty().withMessage('Delivery phone is required'),
  ],
  orderController.createOrder
);

router.get('/', authenticate, orderController.getOrders);

router.get('/:id', authenticate, orderController.getOrderById);

router.put(
  '/:id/status',
  authenticate,
  checkRole('admin'),
  [
    body('status').isIn(['pending', 'confirmed', 'delivered', 'cancelled']).withMessage('Invalid status'),
  ],
  orderController.updateOrderStatus
);

router.delete('/:id', authenticate, orderController.cancelOrder);

module.exports = router;
