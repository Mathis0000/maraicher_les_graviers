const db = require('../config/database');

class PasswordResetToken {
  static async create({ userId, tokenHash, expiresAt }) {
    const query = `
      INSERT INTO password_reset_tokens (user_id, token_hash, expires_at)
      VALUES ($1, $2, $3)
      RETURNING id, user_id, token_hash, expires_at, used_at, created_at
    `;

    const result = await db.query(query, [userId, tokenHash, expiresAt]);
    return result.rows[0];
  }

  static async findValidByTokenHash(tokenHash) {
    const query = `
      SELECT id, user_id, token_hash, expires_at, used_at, created_at
      FROM password_reset_tokens
      WHERE token_hash = $1
        AND used_at IS NULL
        AND expires_at > NOW()
      LIMIT 1
    `;

    const result = await db.query(query, [tokenHash]);
    return result.rows[0];
  }

  static async markUsed(id) {
    const query = `
      UPDATE password_reset_tokens
      SET used_at = NOW()
      WHERE id = $1
      RETURNING id
    `;

    const result = await db.query(query, [id]);
    return result.rows[0];
  }
}

module.exports = PasswordResetToken;
