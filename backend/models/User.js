const db = require('../config/database');
const { hashPassword } = require('../utils/passwordHelper');

class User {
  static async create(userData) {
    const { email, password, firstName, lastName, phone, address, city, postalCode, role = 'customer' } = userData;

    const hashedPassword = await hashPassword(password);

    const query = `
      INSERT INTO users (email, password, first_name, last_name, phone, address, city, postal_code, role)
      VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)
      RETURNING id, email, first_name, last_name, phone, address, city, postal_code, role, created_at
    `;

    const values = [email, hashedPassword, firstName, lastName, phone, address, city, postalCode, role];

    const result = await db.query(query, values);
    return result.rows[0];
  }

  static async findByEmail(email) {
    const query = 'SELECT * FROM users WHERE email = $1';
    const result = await db.query(query, [email]);
    return result.rows[0];
  }

  static async findById(id) {
    const query = 'SELECT id, email, first_name, last_name, phone, address, city, postal_code, role, created_at, updated_at FROM users WHERE id = $1';
    const result = await db.query(query, [id]);
    return result.rows[0];
  }

  static async update(id, userData) {
    const { firstName, lastName, phone, address, city, postalCode } = userData;

    const query = `
      UPDATE users
      SET first_name = COALESCE($1, first_name),
          last_name = COALESCE($2, last_name),
          phone = COALESCE($3, phone),
          address = COALESCE($4, address),
          city = COALESCE($5, city),
          postal_code = COALESCE($6, postal_code)
      WHERE id = $7
      RETURNING id, email, first_name, last_name, phone, address, city, postal_code, role, created_at, updated_at
    `;

    const values = [firstName, lastName, phone, address, city, postalCode, id];

    const result = await db.query(query, values);
    return result.rows[0];
  }
}

module.exports = User;
