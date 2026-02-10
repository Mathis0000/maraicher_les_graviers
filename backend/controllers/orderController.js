const Order = require('../models/Order');
const OrderItem = require('../models/OrderItem');
const Product = require('../models/Product');
const db = require('../config/database');
const { validationResult } = require('express-validator');

const createOrder = async (req, res, next) => {
  const client = await db.pool.connect();

  try {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
      return res.status(400).json({ errors: errors.array() });
    }

    const { items, deliveryAddress, deliveryCity, deliveryPostalCode, deliveryPhone, notes } = req.body;

    if (!items || items.length === 0) {
      return res.status(400).json({ error: 'Order must contain at least one item' });
    }

    await client.query('BEGIN');

    for (const item of items) {
      const stockCheck = await Product.checkStock(item.productId, item.quantity);
      if (!stockCheck.available) {
        await client.query('ROLLBACK');
        return res.status(400).json({
          error: `Product ${item.productId} is not available in requested quantity`,
          details: stockCheck
        });
      }
    }

    let totalAmount = 0;
    const orderItems = [];

    for (const item of items) {
      const product = await Product.findById(item.productId);

      if (!product || !product.is_available) {
        await client.query('ROLLBACK');
        return res.status(400).json({ error: `Product ${item.productId} is not available` });
      }

      const subtotal = product.price * item.quantity;
      totalAmount += subtotal;

      orderItems.push({
        productId: item.productId,
        quantity: item.quantity,
        unitPrice: product.price,
        subtotal
      });
    }

    const order = await Order.create(
      {
        userId: req.user.id,
        totalAmount,
        deliveryAddress,
        deliveryCity,
        deliveryPostalCode,
        deliveryPhone,
        notes
      },
      client
    );

    await OrderItem.createBatch(order.id, orderItems, client);

    for (const item of items) {
      await client.query(
        'UPDATE products SET stock_quantity = stock_quantity - $1 WHERE id = $2',
        [item.quantity, item.productId]
      );
    }

    await client.query('COMMIT');

    const createdOrder = await Order.findById(order.id);

    res.status(201).json({
      message: 'Order created successfully',
      order: createdOrder
    });
  } catch (error) {
    await client.query('ROLLBACK');
    next(error);
  } finally {
    client.release();
  }
};

const getOrders = async (req, res, next) => {
  try {
    let orders;

    if (req.user.role === 'admin') {
      orders = await Order.findAll();
    } else {
      orders = await Order.findByUserId(req.user.id);
    }

    res.json({
      count: orders.length,
      orders
    });
  } catch (error) {
    next(error);
  }
};

const getOrderById = async (req, res, next) => {
  try {
    const { id } = req.params;

    const order = await Order.findById(id);

    if (!order) {
      return res.status(404).json({ error: 'Order not found' });
    }

    if (req.user.role !== 'admin' && order.user_id !== req.user.id) {
      return res.status(403).json({ error: 'Access denied' });
    }

    res.json(order);
  } catch (error) {
    next(error);
  }
};

const updateOrderStatus = async (req, res, next) => {
  try {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
      return res.status(400).json({ errors: errors.array() });
    }

    const { id } = req.params;
    const { status } = req.body;

    const order = await Order.findById(id);

    if (!order) {
      return res.status(404).json({ error: 'Order not found' });
    }

    const updatedOrder = await Order.updateStatus(id, status);

    res.json({
      message: 'Order status updated successfully',
      order: updatedOrder
    });
  } catch (error) {
    next(error);
  }
};

const cancelOrder = async (req, res, next) => {
  const client = await db.pool.connect();

  try {
    const { id } = req.params;

    const order = await Order.findById(id);

    if (!order) {
      return res.status(404).json({ error: 'Order not found' });
    }

    if (req.user.role !== 'admin' && order.user_id !== req.user.id) {
      return res.status(403).json({ error: 'Access denied' });
    }

    if (order.status !== 'pending') {
      return res.status(400).json({ error: 'Only pending orders can be cancelled' });
    }

    await client.query('BEGIN');

    const items = await OrderItem.findByOrderId(id);

    for (const item of items) {
      await client.query(
        'UPDATE products SET stock_quantity = stock_quantity + $1 WHERE id = $2',
        [item.quantity, item.product_id]
      );
    }

    await Order.updateStatus(id, 'cancelled');

    await client.query('COMMIT');

    res.json({
      message: 'Order cancelled successfully'
    });
  } catch (error) {
    await client.query('ROLLBACK');
    next(error);
  } finally {
    client.release();
  }
};

module.exports = {
  createOrder,
  getOrders,
  getOrderById,
  updateOrderStatus,
  cancelOrder
};
