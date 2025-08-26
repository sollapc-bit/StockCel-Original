#!/bin/bash

# SoftwarePar Deployment Script
# This script prepares the application for production deployment

echo "🚀 Iniciando despliegue de SoftwarePar..."

# Create production directory
mkdir -p dist

# Install production dependencies
echo "📦 Instalando dependencias de producción..."
npm install --production

# Build the application
echo "🔨 Construyendo aplicación..."
npm run build

# Set permissions
echo "🔒 Configurando permisos..."
chmod +x dist/server.js

# Create deployment package
echo "📦 Creando paquete de despliegue..."
tar -czf softwarepar-deploy.tar.gz \
  dist/ \
  node_modules/ \
  package.json \
  database.sql \
  .env.example \
  README.md \
  INSTALACION.md \
  attached_assets/

echo "✅ Despliegue completado!"
echo "📁 Archivo de despliegue: softwarepar-deploy.tar.gz"
echo "📖 Ver INSTALACION.md para instrucciones detalladas"