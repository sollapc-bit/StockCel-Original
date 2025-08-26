# Instalación de SoftwarePar (Versión PHP)

## Requisitos del Servidor

- **PHP:** 7.4 o superior
- **MySQL:** 5.7 o superior
- **Servidor Web:** Apache o Nginx
- **Extensiones PHP:** PDO, PDO_MySQL, mbstring, openssl
- **Memoria PHP:** Mínimo 128MB (recomendado 256MB)

## Paso 1: Subir Archivos

### Opción A: Panel de Control (cPanel/DirectAdmin)
1. Acceder al **Administrador de Archivos**
2. Navegar a `public_html/` (o carpeta de tu dominio)
3. Subir el archivo `softwarepar-php.zip`
4. Extraer el contenido del archivo ZIP
5. Eliminar el archivo ZIP

### Opción B: FTP
1. Conectar via FTP a tu servidor
2. Subir todos los archivos a la carpeta `public_html/`
3. Mantener la estructura de carpetas

## Paso 2: Crear Base de Datos

### En cPanel
1. Acceder a **Bases de Datos MySQL**
2. Crear nueva base de datos: `softwarepar`
3. Crear usuario: `softwarepar_user`
4. Generar contraseña segura
5. Asignar **todos los privilegios** al usuario

### En DirectAdmin
1. Ir a **Gestión MySQL**
2. Crear base de datos: `softwarepar`
3. Crear usuario con privilegios completos

## Paso 3: Importar Base de Datos

### Usando phpMyAdmin
1. Acceder a **phpMyAdmin** desde el panel de control
2. Seleccionar la base de datos `softwarepar`
3. Ir a la pestaña **Importar**
4. Seleccionar el archivo `database.sql`
5. Hacer clic en **Continuar**

### Usando línea de comandos (si tienes acceso SSH)
```bash
mysql -u usuario -p softwarepar < database.sql
```

## Paso 4: Configurar Base de Datos

1. Abrir el archivo `config/database.php`
2. Actualizar las credenciales:

```php
private $host = 'localhost';
private $db_name = 'tu_usuario_softwarepar';
private $username = 'tu_usuario_softwarepar_user';
private $password = 'tu_contraseña_mysql';
```

**Nota:** En hosting compartido, el nombre de la base de datos suele ser: `usuario_softwarepar`

## Paso 5: Configurar SSL (Recomendado)

### En cPanel
1. Ir a **SSL/TLS**
2. Activar **Let's Encrypt** para tu dominio
3. Habilitar **Forzar HTTPS**

### En DirectAdmin
1. Acceder a **SSL Certificates**
2. Generar certificado Let's Encrypt
3. Activar redirección HTTPS

## Paso 6: Configurar Permisos

### Permisos recomendados:
- **Carpetas:** 755
- **Archivos:** 644
- **Archivo .htaccess:** 644

### Comando para establecer permisos (SSH):
```bash
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;
```

## Paso 7: Verificar Instalación

1. Abrir `https://tu-dominio.com`
2. Verificar que la página principal cargue correctamente
3. Probar el login con credenciales de prueba

## Usuarios de Prueba

### Administrador
- **Email:** admin@softwarepar.com
- **Contraseña:** password123

### Revendedor
- **Email:** juan@revendedor.com
- **Contraseña:** password123

## Paso 8: Configuración Adicional

### Cambiar Logos
1. Subir tu logo principal como `assets/logo.png`
2. Subir tu logo blanco como `assets/logo_blanco.png`

### Personalizar Información
1. Actualizar información de contacto en `index.php`
2. Cambiar número de WhatsApp en el botón flotante

### Cambiar Contraseñas de Prueba
1. Acceder como administrador
2. Cambiar contraseñas de usuarios de prueba
3. Crear nuevos usuarios según necesites

## Troubleshooting

### Error: "Could not connect to database"
- Verificar credenciales en `config/database.php`
- Confirmar que la base de datos existe
- Revisar permisos del usuario MySQL

### Error: "Headers already sent"
- Verificar que no haya espacios o texto antes de `<?php`
- Revisar codificación de archivos (UTF-8 sin BOM)

### Error: 500 Internal Server Error
- Revisar permisos de archivos (644) y carpetas (755)
- Verificar sintaxis PHP en archivos modificados
- Consultar error_log del servidor

### Error: "Function not found"
- Verificar que las extensiones PHP estén habilitadas
- Contactar al hosting para habilitar PDO_MySQL

## Configuración de Email (Opcional)

Para envío de emails desde el formulario de contacto:

1. Configurar SMTP en el hosting
2. Crear cuenta de email: `info@tu-dominio.com`
3. Actualizar configuración en archivos PHP

## Mantenimiento

### Backup Regular
- Respaldar archivos via FTP
- Exportar base de datos desde phpMyAdmin
- Configurar backups automáticos en el hosting

### Actualizaciones
- Revisar logs de error regularmente
- Actualizar PHP según recomendaciones del hosting
- Monitorear rendimiento de la base de datos

## Soporte

- **WhatsApp:** +54 9 11 6139-6633
- **Email:** info@softwarepar.com

## Seguridad

- Cambiar credenciales de prueba inmediatamente
- Usar contraseñas seguras
- Mantener PHP actualizado
- Revisar logs de acceso regularmente

¡Tu sistema SoftwarePar está listo para usar!