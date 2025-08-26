# 📧 Configuración de Gmail para SMTP

Para que el formulario de contacto funcione correctamente, necesitas configurar Gmail para permitir el envío de correos desde aplicaciones externas.

## 🔐 Paso 1: Habilitar Verificación en 2 Pasos

1. Ve a tu **Cuenta de Google**: https://myaccount.google.com/

2. En el panel izquierdo, selecciona **"Seguridad"**

3. En la sección "Iniciar sesión en Google", asegúrate que **"Verificación en 2 pasos"** esté **ACTIVADA**

   - Si no está activada, haz clic en "Verificación en 2 pasos" y sigue las instrucciones
   - Necesitarás verificar tu identidad con tu teléfono

## 🔑 Paso 2: Generar Contraseña de Aplicación

1. Una vez que tengas la verificación en 2 pasos activada, ve nuevamente a **"Seguridad"**

2. En la sección "Iniciar sesión en Google", busca **"Contraseñas de aplicaciones"**

3. Haz clic en **"Contraseñas de aplicaciones"**

4. En el menú desplegable **"Seleccionar app"**, elige **"Correo"**

5. En **"Seleccionar dispositivo"**, elige **"Otro (nombre personalizado)"**

6. Escribe: **"SoftwarePar Website"**

7. Haz clic en **"GENERAR"**

8. **Google te mostrará una contraseña de 16 caracteres** como: `abcd efgh ijkl mnop`

   ⚠️ **¡IMPORTANTE!** Copia esta contraseña, ya que no podrás verla de nuevo.

## 🔧 Paso 3: Actualizar la Configuración

Una vez que tengas tu **contraseña de aplicación**, actualiza el archivo `enviar_email.php`:

```php
$mail->Username   = 'softwarepar.dev@gmail.com';
$mail->Password   = 'TU_CONTRASEÑA_DE_APLICACION_AQUI'; // Los 16 caracteres que generaste
```

**Ejemplo:**
```php
$mail->Username   = 'softwarepar.dev@gmail.com';
$mail->Password   = 'abcd efgh ijkl mnop'; // Reemplaza con tu contraseña real
```

## 📋 Paso 4: Instalar PHPMailer

En tu servidor, ejecuta estos comandos en el directorio de tu proyecto:

```bash
# Si no tienes Composer instalado
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Instalar PHPMailer
composer install
```

## ✅ Paso 5: Verificar Funcionamiento

1. Sube todos los archivos a tu servidor
2. Asegúrate que el archivo `vendor/` existe (creado por Composer)
3. Prueba el formulario de contacto en tu sitio web
4. Revisa tu bandeja de entrada en `softwarepar.dev@gmail.com`

## 🔍 Solución de Problemas

### Error: "Username and Password not accepted"
- Verifica que estés usando la **contraseña de aplicación**, no tu contraseña normal de Gmail
- Asegúrate que la verificación en 2 pasos esté activada

### Error: "Could not authenticate"
- Revisa que el email `softwarepar.dev@gmail.com` sea correcto
- Verifica que la contraseña de aplicación no tenga espacios extra

### Error: "Connection failed"
- Verifica que tu servidor permita conexiones SMTP salientes al puerto 587
- Algunos hostings bloquean el envío de emails por seguridad

### El formulario no envía
- Revisa que PHPMailer esté instalado correctamente
- Verifica que el archivo `vendor/autoload.php` exista
- Consulta los logs de error de PHP en tu hosting

## 🔒 Seguridad

- **NUNCA** publiques tu contraseña de aplicación en código público
- Considera usar variables de entorno para datos sensibles
- Revisa periódicamente las contraseñas de aplicación activas

## 📞 Contacto de Prueba

Una vez configurado, el formulario enviará emails con esta información:
- **De:** softwarepar.dev@gmail.com  
- **Para:** softwarepar.dev@gmail.com
- **Responder a:** Email del visitante
- **Asunto:** "Nueva consulta desde softwarepar.com - [Nombre]"

¡Tu formulario de contacto estará completamente funcional!