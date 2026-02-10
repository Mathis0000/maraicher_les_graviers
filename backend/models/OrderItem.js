const db = require('../config/database');

class OrderItem {
  static async createBatch(orderId, items, client = null) {
    if (!items || items.length === 0) {
      throw new Error('No items provided');
    }

    const values = [];
    const placeholders = [];

    items.forEach((item, index) => {
      const offset = index * 5;
      placeholders.push(`($${offset + 1}, $${offset + 2}, $${offset + 3}, $${offset + 4}, $${offset + 5})`);
      values.push(orderId, item.productId, item.quantity, item.unitPrice, item.subtotal);
    });

    const query = `
      INSERT INTO order_items (order_id, product_id, quantity, unit_price, subtotal)
      VALUES ${placeholders.join(', ')}
      RETURNING *
    `;

    const dbClient = client || db;
    const result = await dbClient.query(query, values);
    return result.rows;
  }

  static async findByOrderId(orderId) {
    const query = `
      SELECT oi.*, p.name as product_name, p.unit
      FROM order_items oi
      LEFT JOIN products p ON oi.product_id = p.id
      WHERE oi.order_id = $1
    `;

    const result = await db.query(query, [orderId]);
    return result.rows;
  }
}

module.exports = OrderItem;
