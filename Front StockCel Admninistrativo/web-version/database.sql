-- Base de datos para SoftwarePar
-- Versión PHP/MySQL
-- Nota: No incluye CREATE DATABASE ni USE para compatibilidad con el instalador

-- Tabla usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'revendedor') NOT NULL DEFAULT 'revendedor',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla licencias
CREATE TABLE licencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    usuario_id INT NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_vencimiento DATE NOT NULL,
    estado ENUM('activa', 'vencida', 'suspendida') NOT NULL DEFAULT 'activa',
    precio DECIMAL(10, 2),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabla clientes finales
CREATE TABLE clientes_finales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    empresa VARCHAR(100),
    licencia_id INT NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (licencia_id) REFERENCES licencias(id) ON DELETE CASCADE
);

-- Insertar usuario administrador por defecto
INSERT INTO usuarios (nombre, email, password, rol) VALUES 
('Administrador', 'admin@softwarepar.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insertar usuario revendedor de prueba
INSERT INTO usuarios (nombre, email, password, rol) VALUES 
('Juan Pérez', 'juan@revendedor.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'revendedor');

-- Insertar licencias de ejemplo
INSERT INTO licencias (nombre, descripcion, usuario_id, fecha_inicio, fecha_vencimiento, estado, precio) VALUES 
('Sistema de Ventas Pro', 'Sistema completo de gestión de ventas', 2, '2024-01-01', '2024-12-31', 'activa', 299.99),
('Inventario Plus', 'Sistema de gestión de inventario', 2, '2024-01-01', '2024-12-31', 'activa', 199.99),
('CRM Básico', 'Sistema de gestión de clientes', 2, '2023-01-01', '2023-12-31', 'vencida', 149.99);

-- Insertar clientes finales de ejemplo
INSERT INTO clientes_finales (nombre, email, telefono, empresa, licencia_id) VALUES 
('Carlos Rodríguez', 'carlos@empresa1.com', '+54911234567', 'Empresa ABC', 1),
('Ana García', 'ana@empresa2.com', '+54911234568', 'Comercial XYZ', 2),
('Luis Martínez', 'luis@empresa3.com', '+54911234569', 'Servicios DEF', 1);

-- Crear índices para optimización
CREATE INDEX idx_usuarios_email ON usuarios(email);
CREATE INDEX idx_usuarios_rol ON usuarios(rol);
CREATE INDEX idx_licencias_usuario ON licencias(usuario_id);
CREATE INDEX idx_licencias_estado ON licencias(estado);
CREATE INDEX idx_clientes_licencia ON clientes_finales(licencia_id);
CREATE INDEX idx_clientes_email ON clientes_finales(email);

-- Crear vista para estadísticas rápidas
CREATE VIEW vista_estadisticas AS
SELECT 
    (SELECT COUNT(*) FROM usuarios WHERE rol = 'revendedor') as total_revendedores,
    (SELECT COUNT(*) FROM licencias) as total_licencias,
    (SELECT COUNT(*) FROM clientes_finales) as total_clientes,
    (SELECT COUNT(*) FROM licencias WHERE estado = 'activa') as licencias_activas,
    (SELECT COUNT(*) FROM licencias WHERE estado = 'vencida') as licencias_vencidas,
    (SELECT COUNT(*) FROM licencias WHERE estado = 'suspendida') as licencias_suspendidas;

-- Comentarios explicativos
/*
Contraseña para usuarios de prueba: password123

Estructura de la base de datos:
- usuarios: Contiene admins y revendedores
- licencias: Licencias asignadas a revendedores
- clientes_finales: Clientes de cada revendedor

Relaciones:
- Usuario (revendedor) -> Licencias (1:N)
- Licencia -> Clientes Finales (1:N)

Estados de licencia:
- activa: Licencia funcionando normalmente
- vencida: Licencia expirada
- suspendida: Licencia suspendida temporalmente
*/