const express = require('express');
const cors = require('cors');
const helmet = require('helmet');
const morgan = require('morgan');
const path = require('path');
require('dotenv').config();

const db = require('./config/database');
const errorHandler = require('./middleware/errorHandler');

const authRoutes = require('./routes/auth');
const productRoutes = require('./routes/products');
const orderRoutes = require('./routes/orders');

const app = express();

// Disable Helmet completely in development to avoid CSP issues
if (process.env.NODE_ENV !== 'development') {
  app.use(helmet());
} else {
  // In development, set permissive CSP for Vite
  app.use((req, res, next) => {
    res.setHeader('Content-Security-Policy',
      "default-src * 'unsafe-inline' 'unsafe-eval' data: blob:; " +
      "script-src * 'unsafe-inline' 'unsafe-eval'; " +
      "style-src * 'unsafe-inline'; " +
      "img-src * data: blob:; " +
      "font-src *; " +
      "connect-src *;"
    );
    next();
  });
}

app.use(cors());
app.use(morgan('dev'));
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

app.use('/uploads', express.static(path.join(__dirname, 'uploads')));

app.use('/api/auth', authRoutes);
app.use('/api/products', productRoutes);
app.use('/api/orders', orderRoutes);

app.get('/api/health', (req, res) => {
  res.json({
    status: 'ok',
    timestamp: new Date().toISOString(),
    uptime: process.uptime()
  });
});

app.use((req, res) => {
  res.status(404).json({ error: 'Route not found' });
});

app.use(errorHandler);

const PORT = process.env.PORT || 5000;

app.listen(PORT, () => {
  console.log(`
╔════════════════════════════════════════╗
║   Maraîcher E-Commerce Backend API    ║
╠════════════════════════════════════════╣
║   Server running on port ${PORT}         ║
║   Environment: ${process.env.NODE_ENV || 'development'}             ║
║   URL: http://localhost:${PORT}          ║
╚════════════════════════════════════════╝
  `);
});

process.on('SIGTERM', () => {
  console.log('SIGTERM signal received: closing HTTP server');
  server.close(() => {
    console.log('HTTP server closed');
    db.pool.end(() => {
      console.log('Database pool closed');
      process.exit(0);
    });
  });
});
