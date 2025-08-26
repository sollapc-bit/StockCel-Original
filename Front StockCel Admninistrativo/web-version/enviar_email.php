<?php
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Obtener datos del formulario
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    $input = $_POST;
}

$nombre = isset($input['nombre']) ? trim($input['nombre']) : '';
$email = isset($input['email']) ? trim($input['email']) : '';
$mensaje = isset($input['mensaje']) ? trim($input['mensaje']) : '';

// Validaciones
if (empty($nombre) || empty($email) || empty($mensaje)) {
    echo json_encode([
        'success' => false, 
        'message' => 'Todos los campos son obligatorios'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false, 
        'message' => 'El email no es válido'
    ]);
    exit;
}

try {
    $mail = new PHPMailer(true);

    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'softwarepar.dev@gmail.com';
    $mail->Password   = 'Kc5bpdfkr@';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Configuración del mensaje
    $mail->setFrom('softwarepar.dev@gmail.com', 'SoftwarePar - Formulario Web');
    $mail->addAddress('softwarepar.dev@gmail.com', 'SoftwarePar');
    $mail->addReplyTo($email, $nombre);

    // Contenido del mensaje
    $mail->isHTML(true);
    $mail->Subject = 'Nueva consulta desde softwarepar.com - ' . $nombre;
    
    $htmlBody = "
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .header { background-color: #2563eb; color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; background-color: #f9f9f9; }
            .info-box { background-color: white; padding: 15px; margin: 10px 0; border-left: 4px solid #2563eb; }
            .footer { text-align: center; padding: 20px; background-color: #1f2937; color: white; }
        </style>
    </head>
    <body>
        <div class='header'>
            <h2>Nueva Consulta - SoftwarePar</h2>
        </div>
        
        <div class='content'>
            <div class='info-box'>
                <h3>Información del Cliente</h3>
                <p><strong>Nombre:</strong> " . htmlspecialchars($nombre) . "</p>
                <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
                <p><strong>Fecha:</strong> " . date('d/m/Y H:i:s') . "</p>
            </div>
            
            <div class='info-box'>
                <h3>Mensaje</h3>
                <p>" . nl2br(htmlspecialchars($mensaje)) . "</p>
            </div>
            
            <div class='info-box'>
                <h3>Origen</h3>
                <p><strong>Sitio Web:</strong> softwarepar.com</p>
                <p><strong>IP:</strong> " . $_SERVER['REMOTE_ADDR'] . "</p>
                <p><strong>User Agent:</strong> " . $_SERVER['HTTP_USER_AGENT'] . "</p>
            </div>
        </div>
        
        <div class='footer'>
            <p>Este mensaje fue enviado desde el formulario de contacto de SoftwarePar</p>
            <p>Para responder, utiliza el email: " . htmlspecialchars($email) . "</p>
        </div>
    </body>
    </html>";

    $mail->Body = $htmlBody;
    
    // Versión texto plano
    $mail->AltBody = "
    Nueva consulta desde SoftwarePar
    
    Nombre: $nombre
    Email: $email
    Fecha: " . date('d/m/Y H:i:s') . "
    
    Mensaje:
    $mensaje
    
    ---
    Enviado desde: softwarepar.com
    IP: " . $_SERVER['REMOTE_ADDR'];

    $mail->send();
    
    echo json_encode([
        'success' => true, 
        'message' => '¡Mensaje enviado exitosamente! Te contactaremos pronto.'
    ]);

} catch (Exception $e) {
    error_log("Error PHPMailer: " . $mail->ErrorInfo);
    
    echo json_encode([
        'success' => false, 
        'message' => 'Error al enviar el mensaje. Por favor, inténtalo más tarde o contáctanos por WhatsApp.'
    ]);
}
?>