#!/usr/bin/env node

const { build } = require('esbuild');
const fs = require('fs');
const path = require('path');

// Build server
build({
  entryPoints: ['server/index.ts'],
  bundle: true,
  outfile: 'dist/server.js',
  platform: 'node',
  format: 'cjs',
  target: 'node18',
  external: ['mysql2', 'bcrypt'],
  minify: true,
  sourcemap: true,
}).then(() => {
  console.log('✅ Server built successfully');
}).catch(() => process.exit(1));

// Build client
build({
  entryPoints: ['client/src/main.tsx'],
  bundle: true,
  outdir: 'dist/public',
  platform: 'browser',
  format: 'esm',
  target: 'es2020',
  minify: true,
  sourcemap: true,
  define: {
    'process.env.NODE_ENV': '"production"',
  },
  loader: {
    '.png': 'file',
    '.jpg': 'file',
    '.jpeg': 'file',
    '.svg': 'file',
    '.gif': 'file',
    '.woff': 'file',
    '.woff2': 'file',
    '.ttf': 'file',
    '.eot': 'file',
  },
}).then(() => {
  console.log('✅ Client built successfully');
}).catch(() => process.exit(1));

// Copy static files
if (!fs.existsSync('dist/public')) {
  fs.mkdirSync('dist/public', { recursive: true });
}

// Copy HTML template
const htmlTemplate = `
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SoftwarePar - Sistemas Administrativos</title>
  <meta name="description" content="Sistemas administrativos personalizados para empresas con modelo de licenciamiento flexible">
  <link rel="stylesheet" href="/main.css">
</head>
<body>
  <div id="root"></div>
  <script src="/main.js"></script>
</body>
</html>
`;

fs.writeFileSync('dist/public/index.html', htmlTemplate);

// Copy assets
if (fs.existsSync('attached_assets')) {
  const assetsDir = 'dist/public/assets';
  if (!fs.existsSync(assetsDir)) {
    fs.mkdirSync(assetsDir, { recursive: true });
  }
  
  fs.readdirSync('attached_assets').forEach(file => {
    fs.copyFileSync(
      path.join('attached_assets', file),
      path.join(assetsDir, file)
    );
  });
}

console.log('✅ Build completed successfully');