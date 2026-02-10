const db = require('../config/database');

class Product {
  static async create(productData) {
    const { name, description, price, imageUrl, stockQuantity, unit, category, isAvailable = true } = productData;

    const query = `
      INSERT INTO products (name, description, price, image_url, stock_quantity, unit, category, is_available)
      VALUES ($1, $2, $3, $4, $5, $6, $7, $8)
      RETURNING *
    `;

    const values = [name, description, price, imageUrl, stockQuantity, unit, category, isAvailable];

    const result = await db.query(query, values);
    return result.rows[0];
  }

  static async findAll(filters = {}) {
    let query = 'SELECT * FROM products';
    const values = [];
    const conditions = [];

    if (filters.isAvailable !== undefined) {
      conditions.push(`is_available = $${values.length + 1}`);
      values.push(filters.isAvailable);
    }

    if (filters.category) {
      conditions.push(`category = $${values.length + 1}`);
      values.push(filters.category);
    }

    if (conditions.length > 0) {
      query += ' WHERE ' + conditions.join(' AND ');
    }

    query += ' ORDER BY created_at DESC';

    const result = await db.query(query, values);
    return result.rows;
  }

  static async findById(id) {
    const query = 'SELECT * FROM products WHERE id = $1';
    const result = await db.query(query, [id]);
    return result.rows[0];
  }

  static async update(id, productData) {
    const { name, description, price, imageUrl, stockQuantity, unit, category, isAvailable } = productData;

    const query = `
      UPDATE products
      SET name = COALESCE($1, name),
          description = COALESCE($2, description),
          price = COALESCE($3, price),
          image_url = COALESCE($4, image_url),
          stock_quantity = COALESCE($5, stock_quantity),
          unit = COALESCE($6, unit),
          category = COALESCE($7, category),
          is_available = COALESCE($8, is_available)
      WHERE id = $9
      RETURNING *
    `;

    const values = [name, description, price, imageUrl, stockQuantity, unit, category, isAvailable, id];

    const result = await db.query(query, values);
    return result.rows[0];
  }

  static async delete(id) {
    const query = 'DELETE FROM products WHERE id = $1 RETURNING *';
    const result = await db.query(query, [id]);
    return result.rows[0];
  }

  static async decrementStock(productId, quantity) {
    const query = `
      UPDATE products
      SET stock_quantity = stock_quantity - $1
      WHERE id = $2 AND stock_quantity >= $1
      RETURNING *
    `;
    const result = await db.query(query, [quantity, productId]);
    return result.rows[0];
  }

  static async checkStock(productId, requiredQuantity) {
    const product = await this.findById(productId);
    if (!product) {
      return { available: false, message: 'Product not found' };
    }
    if (product.stock_quantity < requiredQuantity) {
      return { available: false, message: 'Insufficient stock', currentStock: product.stock_quantity };
    }
    return { available: true, product };
  }
}

module.exports = Product;
