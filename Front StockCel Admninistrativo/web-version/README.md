# SoftwarePar - Versión PHP

Sistema de gestión de licencias para revendedores desarrollado en PHP y MySQL.

## Características

- **Página de inicio profesional** con información de la empresa
- **Panel de administración** para gestión completa del sistema
- **Panel de revendedor** para gestión de licencias y clientes
- **Sistema de autenticación** con roles de usuario
- **Base de datos MySQL** optimizada
- **Diseño responsive** con Bootstrap 5
- **Botón WhatsApp** integrado

## Requisitos del Servidor

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web Apache/Nginx
- Extensiones PHP: PDO, PDO_MySQL

## Instalación

### 1. Subir Archivos
- Subir todos los archivos a tu servidor web
- Asegurar que los archivos estén en la carpeta `public_html/` o equivalente

### 2. Crear Base de Datos
- Crear una base de datos MySQL llamada `softwarepar`
- Importar el archivo `database.sql` en la base de datos

### 3. Configurar Base de Datos
- Editar el archivo `config/database.php`
- Actualizar las credenciales de la base de datos:
  ```php
  private $host = 'localhost';
  private $db_name = 'softwarepar';
  private $username = 'tu_usuario';
  private $password = 'tu_contraseña';
  ```

### 4. Configurar Permisos
- Asegurar que las carpetas tengan permisos 755
- Asegurar que los archivos tengan permisos 644

## Usuarios de Prueba

### Administrador
- **Email:** admin@softwarepar.com
- **Contraseña:** password123

### Revendedor
- **Email:** juan@revendedor.com
- **Contraseña:** password123

## Estructura del Proyecto

```
web-version/
├── assets/
│   ├── css/
│   │   └── admin.css
│   ├── logo.png
│   └── logo_blanco.png
├── config/
│   └── database.php
├── admin/
│   ├── dashboard.php
│   ├── revendedores.php
│   ├── licencias.php
│   ├── clientes.php
│   └── logout.php
├── revendedor/
│   ├── dashboard.php
│   ├── licencias.php
│   ├── clientes.php
│   └── logout.php
├── index.php
├── login.php
└── database.sql
```

## Funcionalidades

### Panel de Administración
- Dashboard con estadísticas del sistema
- Gestión de revendedores (crear, eliminar)
- Gestión de licencias (crear, eliminar, asignar)
- Visualización de todos los clientes finales

### Panel de Revendedor
- Dashboard con estadísticas personales
- Visualización de licencias asignadas
- Gestión de clientes finales
- Creación y eliminación de clientes

### Página Principal
- Diseño profesional y moderno
- Información de servicios
- Formulario de contacto
- Botón WhatsApp flotante

## Personalización

### Cambiar Logos
- Reemplazar `assets/logo.png` con tu logo principal
- Reemplazar `assets/logo_blanco.png` con tu logo para fondos oscuros

### Cambiar Colores
- Editar las variables CSS en `assets/css/admin.css`
- Modificar los colores en los archivos PHP según necesites

### Cambiar Información de Contacto
- Editar el número de WhatsApp en `index.php`
- Actualizar la información de contacto en el footer

## Soporte

- **WhatsApp:** +54 9 11 6139-6633
- **Email:** info@softwarepar.com

## Licencia

Este proyecto está desarrollado para SoftwarePar y es de uso privado.