<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar si ya está instalado
if (file_exists('config/installed.lock')) {
    die('
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ya Instalado - SoftwarePar</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <h3 class="text-success">✅ Sistema Ya Instalado</h3>
                            <p class="text-muted">El sistema SoftwarePar ya ha sido instalado correctamente.</p>
                            <a href="index.php" class="btn btn-primary">Ir al Inicio</a>
                            <a href="login.php" class="btn btn-success">Iniciar Sesión</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
    ');
}

$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$error = '';
$success = '';

// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($step == 1) {
        // Verificar requisitos del sistema
        $requirements_passed = true;
        $requirements = [];
        
        // Verificar PHP
        $requirements['php_version'] = version_compare(PHP_VERSION, '7.4.0', '>=');
        if (!$requirements['php_version']) {
            $requirements_passed = false;
            $error = 'Se requiere PHP 7.4 o superior. Versión actual: ' . PHP_VERSION;
        }
        
        // Verificar extensiones PHP
        $requirements['pdo'] = extension_loaded('pdo');
        $requirements['pdo_mysql'] = extension_loaded('pdo_mysql');
        $requirements['mbstring'] = extension_loaded('mbstring');
        
        if (!$requirements['pdo'] || !$requirements['pdo_mysql'] || !$requirements['mbstring']) {
            $requirements_passed = false;
            $error = 'Faltan extensiones PHP requeridas.';
        }
        
        // Verificar permisos de escritura
        $requirements['config_writable'] = is_writable('config/');
        if (!$requirements['config_writable']) {
            $requirements_passed = false;
            $error = 'La carpeta config/ debe tener permisos de escritura.';
        }
        
        if ($requirements_passed) {
            header('Location: install.php?step=2');
            exit;
        }
    } elseif ($step == 2) {
        // Configurar base de datos
        $db_host = $_POST['db_host'];
        $db_name = $_POST['db_name'];
        $db_user = $_POST['db_user'];
        $db_pass = $_POST['db_pass'];
        $db_port = $_POST['db_port'] ?: '3306';
        
        try {
            // Probar conexión
            $dsn = "mysql:host=$db_host;port=$db_port;charset=utf8mb4";
            $pdo = new PDO($dsn, $db_user, $db_pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Verificar si la base de datos existe, si no, crearla
            $stmt = $pdo->prepare("SHOW DATABASES LIKE ?");
            $stmt->execute([$db_name]);
            if (!$stmt->fetch()) {
                $pdo->exec("CREATE DATABASE `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            }
            
            // Conectar a la base de datos específica
            $dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8mb4";
            $pdo = new PDO($dsn, $db_user, $db_pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Guardar configuración en sesión
            session_start();
            $_SESSION['install_config'] = [
                'db_host' => $db_host,
                'db_name' => $db_name,
                'db_user' => $db_user,
                'db_pass' => $db_pass,
                'db_port' => $db_port
            ];
            
            header('Location: install.php?step=3');
            exit;
            
        } catch (Exception $e) {
            $error = 'Error de conexión: ' . $e->getMessage();
        }
    } elseif ($step == 3) {
        // Crear usuario administrador
        session_start();
        if (!isset($_SESSION['install_config'])) {
            header('Location: install.php?step=2');
            exit;
        }
        
        $admin_name = $_POST['admin_name'];
        $admin_email = $_POST['admin_email'];
        $admin_password = $_POST['admin_password'];
        $confirm_password = $_POST['confirm_password'];
        
        if ($admin_password !== $confirm_password) {
            $error = 'Las contraseñas no coinciden.';
        } elseif (strlen($admin_password) < 6) {
            $error = 'La contraseña debe tener al menos 6 caracteres.';
        } else {
            $_SESSION['admin_config'] = [
                'admin_name' => $admin_name,
                'admin_email' => $admin_email,
                'admin_password' => $admin_password
            ];
            
            header('Location: install.php?step=4');
            exit;
        }
    } elseif ($step == 4) {
        // Ejecutar instalación
        session_start();
        if (!isset($_SESSION['install_config']) || !isset($_SESSION['admin_config'])) {
            header('Location: install.php?step=1');
            exit;
        }
        
        $config = $_SESSION['install_config'];
        $admin = $_SESSION['admin_config'];
        
        try {
            // Conectar a la base de datos
            $dsn = "mysql:host={$config['db_host']};port={$config['db_port']};dbname={$config['db_name']};charset=utf8mb4";
            $pdo = new PDO($dsn, $config['db_user'], $config['db_pass']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Leer y ejecutar SQL
            $sql = file_get_contents('database.sql');
            
            // Remover comentarios y líneas vacías
            $sql_lines = explode("\n", $sql);
            $clean_statements = [];
            $current_statement = '';
            
            foreach ($sql_lines as $line) {
                $line = trim($line);
                
                // Saltar líneas vacías y comentarios
                if (empty($line) || strpos($line, '--') === 0 || strpos($line, '/*') === 0) {
                    continue;
                }
                
                $current_statement .= ' ' . $line;
                
                // Si la línea termina con punto y coma, es el final de una declaración
                if (substr($line, -1) === ';') {
                    $clean_statements[] = trim($current_statement);
                    $current_statement = '';
                }
            }
            
            // Ejecutar cada declaración por separado
            foreach ($clean_statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement)) {
                    try {
                        $pdo->exec($statement);
                    } catch (PDOException $e) {
                        // Log el error pero continúa con las demás declaraciones
                        error_log("Error ejecutando SQL: " . $statement . " - " . $e->getMessage());
                    }
                }
            }
            
            // Crear usuario administrador personalizado
            $hashed_password = password_hash($admin['admin_password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, email = ?, password = ? WHERE rol = 'admin' LIMIT 1");
            $stmt->execute([$admin['admin_name'], $admin['admin_email'], $hashed_password]);
            
            // Crear archivo de configuración
            $config_content = "<?php
// Configuración de base de datos generada por el instalador
class Database {
    private \$host = '{$config['db_host']}';
    private \$db_name = '{$config['db_name']}';
    private \$username = '{$config['db_user']}';
    private \$password = '{$config['db_pass']}';
    private \$port = '{$config['db_port']}';
    private \$conn;
    
    public function getConnection() {
        \$this->conn = null;
        
        try {
            \$this->conn = new PDO(\"mysql:host=\" . \$this->host . \";port=\" . \$this->port . \";dbname=\" . \$this->db_name . \";charset=utf8mb4\", 
                                 \$this->username, \$this->password);
            \$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException \$exception) {
            echo \"Error de conexión: \" . \$exception->getMessage();
        }
        
        return \$this->conn;
    }
}

// Crear instancia de base de datos
\$database = new Database();
\$pdo = \$database->getConnection();

// Verificar conexión
if (!\$pdo) {
    die(\"No se pudo conectar a la base de datos\");
}
?>";
            
            file_put_contents('config/database.php', $config_content);
            
            // Crear archivo de bloqueo
            file_put_contents('config/installed.lock', date('Y-m-d H:i:s'));
            
            // Limpiar sesión
            unset($_SESSION['install_config']);
            unset($_SESSION['admin_config']);
            
            header('Location: install.php?step=5');
            exit;
            
        } catch (Exception $e) {
            $error = 'Error durante la instalación: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalador SoftwarePar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .installer-card {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            border: none;
        }
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }
        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            font-weight: bold;
        }
        .step.active {
            background: #007bff;
            color: white;
        }
        .step.completed {
            background: #28a745;
            color: white;
        }
        .logo {
            max-height: 60px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card installer-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <img src="assets/logo.png" alt="SoftwarePar" class="logo">
                            <h2 class="text-primary">Instalador SoftwarePar</h2>
                            <p class="text-muted">Sistema de gestión de licencias</p>
                        </div>

                        <!-- Step Indicator -->
                        <div class="step-indicator">
                            <div class="step <?php echo $step >= 1 ? ($step == 1 ? 'active' : 'completed') : ''; ?>">1</div>
                            <div class="step <?php echo $step >= 2 ? ($step == 2 ? 'active' : 'completed') : ''; ?>">2</div>
                            <div class="step <?php echo $step >= 3 ? ($step == 3 ? 'active' : 'completed') : ''; ?>">3</div>
                            <div class="step <?php echo $step >= 4 ? ($step == 4 ? 'active' : 'completed') : ''; ?>">4</div>
                            <div class="step <?php echo $step >= 5 ? 'active' : ''; ?>">5</div>
                        </div>

                        <?php if ($error): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check me-2"></i>
                                <?php echo htmlspecialchars($success); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($step == 1): ?>
                            <!-- Paso 1: Verificar Requisitos -->
                            <h4><i class="fas fa-cog me-2"></i>Verificar Requisitos del Sistema</h4>
                            <p class="text-muted mb-4">Verificamos que tu servidor cumpla con los requisitos mínimos.</p>
                            
                            <div class="requirements-check">
                                <div class="row mb-3">
                                    <div class="col-8">PHP 7.4 o superior</div>
                                    <div class="col-4 text-end">
                                        <?php if (version_compare(PHP_VERSION, '7.4.0', '>=')): ?>
                                            <span class="badge bg-success"><i class="fas fa-check"></i> <?php echo PHP_VERSION; ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-danger"><i class="fas fa-times"></i> <?php echo PHP_VERSION; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-8">Extensión PDO</div>
                                    <div class="col-4 text-end">
                                        <span class="badge bg-<?php echo extension_loaded('pdo') ? 'success' : 'danger'; ?>">
                                            <i class="fas fa-<?php echo extension_loaded('pdo') ? 'check' : 'times'; ?>"></i>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-8">Extensión PDO MySQL</div>
                                    <div class="col-4 text-end">
                                        <span class="badge bg-<?php echo extension_loaded('pdo_mysql') ? 'success' : 'danger'; ?>">
                                            <i class="fas fa-<?php echo extension_loaded('pdo_mysql') ? 'check' : 'times'; ?>"></i>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-8">Extensión mbstring</div>
                                    <div class="col-4 text-end">
                                        <span class="badge bg-<?php echo extension_loaded('mbstring') ? 'success' : 'danger'; ?>">
                                            <i class="fas fa-<?php echo extension_loaded('mbstring') ? 'check' : 'times'; ?>"></i>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-8">Carpeta config/ escribible</div>
                                    <div class="col-4 text-end">
                                        <span class="badge bg-<?php echo is_writable('config/') ? 'success' : 'danger'; ?>">
                                            <i class="fas fa-<?php echo is_writable('config/') ? 'check' : 'times'; ?>"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <form method="POST" class="mt-4">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-arrow-right me-2"></i>Continuar
                                    </button>
                                </div>
                            </form>

                        <?php elseif ($step == 2): ?>
                            <!-- Paso 2: Configuración de Base de Datos -->
                            <h4><i class="fas fa-database me-2"></i>Configuración de Base de Datos</h4>
                            <p class="text-muted mb-4">Configura la conexión a tu base de datos MySQL.</p>
                            
                            <form method="POST">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="db_host" class="form-label">Servidor de Base de Datos</label>
                                            <input type="text" class="form-control" name="db_host" id="db_host" 
                                                   value="localhost" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="db_port" class="form-label">Puerto</label>
                                            <input type="number" class="form-control" name="db_port" id="db_port" 
                                                   value="3306" placeholder="3306">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="db_name" class="form-label">Nombre de la Base de Datos</label>
                                    <input type="text" class="form-control" name="db_name" id="db_name" 
                                           value="softwarepar" required>
                                    <div class="form-text">Si no existe, se creará automáticamente.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="db_user" class="form-label">Usuario de la Base de Datos</label>
                                    <input type="text" class="form-control" name="db_user" id="db_user" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="db_pass" class="form-label">Contraseña de la Base de Datos</label>
                                    <input type="password" class="form-control" name="db_pass" id="db_pass">
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-plug me-2"></i>Probar Conexión y Continuar
                                    </button>
                                </div>
                            </form>

                        <?php elseif ($step == 3): ?>
                            <!-- Paso 3: Configurar Administrador -->
                            <h4><i class="fas fa-user-shield me-2"></i>Configurar Usuario Administrador</h4>
                            <p class="text-muted mb-4">Crea tu cuenta de administrador para acceder al sistema.</p>
                            
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="admin_name" class="form-label">Nombre Completo</label>
                                    <input type="text" class="form-control" name="admin_name" id="admin_name" 
                                           value="Administrador" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="admin_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" name="admin_email" id="admin_email" 
                                           value="admin@softwarepar.com" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="admin_password" class="form-label">Contraseña</label>
                                    <input type="password" class="form-control" name="admin_password" id="admin_password" 
                                           minlength="6" required>
                                    <div class="form-text">Mínimo 6 caracteres.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" 
                                           minlength="6" required>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-arrow-right me-2"></i>Continuar
                                    </button>
                                </div>
                            </form>

                        <?php elseif ($step == 4): ?>
                            <!-- Paso 4: Instalación -->
                            <h4><i class="fas fa-cogs me-2"></i>Ejecutando Instalación</h4>
                            <p class="text-muted mb-4">Creando base de datos y configurando el sistema...</p>
                            
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Instalando...</span>
                                </div>
                                <p class="mt-3">Por favor espera mientras se completa la instalación.</p>
                            </div>
                            
                            <form method="POST" id="installForm">
                                <input type="hidden" name="execute_install" value="1">
                            </form>
                            
                            <script>
                                setTimeout(function() {
                                    document.getElementById('installForm').submit();
                                }, 2000);
                            </script>

                        <?php elseif ($step == 5): ?>
                            <!-- Paso 5: Instalación Completada -->
                            <div class="text-center">
                                <div class="mb-4">
                                    <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                                </div>
                                <h4 class="text-success">¡Instalación Completada!</h4>
                                <p class="text-muted mb-4">Tu sistema SoftwarePar ha sido instalado correctamente.</p>
                                
                                <div class="alert alert-info text-start">
                                    <h6><i class="fas fa-info-circle me-2"></i>Información Importante:</h6>
                                    <ul class="mb-0">
                                        <li>Elimina o renombra el archivo <code>install.php</code> por seguridad</li>
                                        <li>Tu cuenta de administrador está lista para usar</li>
                                        <li>El usuario de prueba "revendedor" sigue disponible (email: juan@revendedor.com, contraseña: password123)</li>
                                    </ul>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <a href="index.php" class="btn btn-primary btn-lg">
                                        <i class="fas fa-home me-2"></i>Ir al Sitio Web
                                    </a>
                                    <a href="login.php" class="btn btn-success btn-lg">
                                        <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <small class="text-white-50">
                        <i class="fab fa-whatsapp me-1"></i>
                        Soporte: +54 9 11 6139-6633
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>