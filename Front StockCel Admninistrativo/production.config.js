// Production Configuration for SoftwarePar
module.exports = {
  // Database configuration
  database: {
    host: process.env.DB_HOST || 'localhost',
    port: process.env.DB_PORT || 3306,
    user: process.env.DB_USER || 'root',
    password: process.env.DB_PASSWORD || '',
    database: process.env.DB_NAME || 'softwarepar',
    ssl: process.env.DB_SSL === 'true' ? { rejectUnauthorized: false } : false
  },

  // Server configuration
  server: {
    port: process.env.PORT || 5000,
    host: '0.0.0.0',
    session: {
      secret: process.env.SESSION_SECRET || 'change-this-in-production',
      secure: process.env.NODE_ENV === 'production',
      maxAge: 24 * 60 * 60 * 1000 // 24 hours
    }
  },

  // Application settings
  app: {
    name: 'SoftwarePar',
    version: '1.0.0',
    environment: process.env.NODE_ENV || 'production'
  }
};