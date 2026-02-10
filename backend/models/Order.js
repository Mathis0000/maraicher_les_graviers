const db = require('../config/database');

class Order {
  static async create(orderData, client = null) {
    const { userId, totalAmount, deliveryAddress, deliveryCity, deliveryPostalCode, deliveryPhone, notes } = orderData;

    const query = `
      INSERT INTO orders (user_id, total_amount, delivery_address, delivery_city, delivery_postal_code, delivery_phone, notes, status)
      VALUES ($1, $2, $3, $4, $5, $6, $7, 'pending')
      RETURNING *
    `;

    const values = [userId, totalAmount, deliveryAddress, deliveryCity, deliveryPostalCode, deliveryPhone, notes];

    const dbClient = client || db;
    const result = await dbClient.query(query, values);
    return result.rows[0];
  }

  static async findByUserId(userId) {
    const query = `
      SELECT o.*,
             json_agg(
               json_build_object(
                 'id', oi.id,
                 'product_id', oi.product_id,
                 'product_name', p.name,
                 'quantity', oi.quantity,
                 'unit_price', oi.unit_price,
                 'subtotal', oi.subtotal,
                 'unit', p.unit
               )
             ) as items
      FROM orders o
      LEFT JOIN order_items oi ON o.id = oi.order_id
      LEFT JOIN products p ON oi.product_id = p.id
      WHERE o.user_id = $1
      GROUP BY o.id
      ORDER BY o.created_at DESC
    `;

    const result = await db.query(query, [userId]);
    return result.rows;
  }

  static async findAll() {
    const query = `
      SELECT o.*,
             u.email, u.first_name, u.last_name,
             json_agg(
               json_build_object(
                 'id', oi.id,
                 'product_id', oi.product_id,
                 'product_name', p.name,
                 'quantity', oi.quantity,
                 'unit_price', oi.unit_price,
                 'subtotal', oi.subtotal,
                 'unit', p.unit
               )
             ) as items
      FROM orders o
      LEFT JOIN users u ON o.user_id = u.id
      LEFT JOIN order_items oi ON o.id = oi.order_id
      LEFT JOIN products p ON oi.product_id = p.id
      GROUP BY o.id, u.email, u.first_name, u.last_name
      ORDER BY o.created_at DESC
    `;

    const result = await db.query(query);
    return result.rows;
  }

  static async findById(orderId) {
    const query = `
      SELECT o.*,
             u.email, u.first_name, u.last_name,
             json_agg(
               json_build_object(
                 'id', oi.id,
                 'product_id', oi.product_id,
                 'product_name', p.name,
                 'quantity', oi.quantity,
                 'unit_price', oi.unit_price,
                 'subtotal', oi.subtotal,
                 'unit', p.unit,
                 'image_url', p.image_url
               )
             ) as items
      FROM orders o
      LEFT JOIN users u ON o.user_id = u.id
      LEFT JOIN order_items oi ON o.id = oi.order_id
      LEFT JOIN products p ON oi.product_id = p.id
      WHERE o.id = $1
      GROUP BY o.id, u.email, u.first_name, u.last_name
    `;

    const result = await db.query(query, [orderId]);
    return result.rows[0];
  }

  static async updateStatus(orderId, status) {
    const validStatuses = ['pending', 'confirmed', 'delivered', 'cancelled'];
    if (!validStatuses.includes(status)) {
      throw new Error('Invalid status');
    }

    const query = `
      UPDATE orders
      SET status = $1
      WHERE id = $2
      RETURNING *
    `;

    const result = await db.query(query, [status, orderId]);
    return result.rows[0];
  }

  static async delete(orderId) {
    const query = 'DELETE FROM orders WHERE id = $1 RETURNING *';
    const result = await db.query(query, [orderId]);
    return result.rows[0];
  }
}

module.exports = Order;
