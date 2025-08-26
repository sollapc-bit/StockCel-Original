<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'revendedor') {
    header('Location: ../login.php');
    exit;
}

require_once '../config/database.php';

$userId = $_SESSION['user_id'];

// Obtener estadísticas del revendedor
try {
    $stats = [];
    
    // Licencias asignadas al revendedor
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM licencias WHERE usuario_id = ?");
    $stmt->execute([$userId]);
    $stats['licencias'] = $stmt->fetchColumn();
    
    // Clientes del revendedor
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM clientes_finales cf 
                          JOIN licencias l ON cf.licencia_id = l.id 
                          WHERE l.usuario_id = ?");
    $stmt->execute([$userId]);
    $stats['clientes'] = $stmt->fetchColumn();
    
    // Licencias activas
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM licencias WHERE usuario_id = ? AND estado = 'activa'");
    $stmt->execute([$userId]);
    $stats['activas'] = $stmt->fetchColumn();
    
    // Licencias vencidas
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM licencias WHERE usuario_id = ? AND estado = 'vencida'");
    $stmt->execute([$userId]);
    $stats['vencidas'] = $stmt->fetchColumn();
    
    // Licencias suspendidas
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM licencias WHERE usuario_id = ? AND estado = 'suspendida'");
    $stmt->execute([$userId]);
    $stats['suspendidas'] = $stmt->fetchColumn();
    
} catch(Exception $e) {
    $error = "Error al obtener estadísticas: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Revendedor - SoftwarePar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/admin.css" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <img src="../assets/logo.png" alt="SoftwarePar" class="logo">
                <h4>Panel Revendedor</h4>
            </div>
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="dashboard.php">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="licencias.php">
                        <i class="fas fa-key"></i> Mis Licencias
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="clientes.php">
                        <i class="fas fa-user-friends"></i> Mis Clientes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div id="content" class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h1>Dashboard Revendedor</h1>
                            <div class="user-info">
                                <span class="me-3">Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                                <span class="badge bg-success"><?php echo ucfirst($_SESSION['user_role']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="card stat-card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4><?php echo $stats['licencias']; ?></h4>
                                        <p class="card-text">Mis Licencias</p>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-key"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6">
                        <div class="card stat-card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4><?php echo $stats['clientes']; ?></h4>
                                        <p class="card-text">Mis Clientes</p>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-user-friends"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6">
                        <div class="card stat-card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4><?php echo $stats['activas']; ?></h4>
                                        <p class="card-text">Licencias Activas</p>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6">
                        <div class="card stat-card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4><?php echo $stats['vencidas']; ?></h4>
                                        <p class="card-text">Licencias Vencidas</p>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- License Status Chart -->
                <div class="row mt-4">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Estado de Mis Licencias</h5>
                            </div>
                            <div class="card-body">
                                <div class="progress-group">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Activas</span>
                                        <span><?php echo $stats['activas']; ?></span>
                                    </div>
                                    <div class="progress mb-3">
                                        <div class="progress-bar bg-success" style="width: <?php echo $stats['licencias'] > 0 ? ($stats['activas'] / $stats['licencias'] * 100) : 0; ?>%"></div>
                                    </div>
                                </div>
                                
                                <div class="progress-group">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Vencidas</span>
                                        <span><?php echo $stats['vencidas']; ?></span>
                                    </div>
                                    <div class="progress mb-3">
                                        <div class="progress-bar bg-danger" style="width: <?php echo $stats['licencias'] > 0 ? ($stats['vencidas'] / $stats['licencias'] * 100) : 0; ?>%"></div>
                                    </div>
                                </div>
                                
                                <div class="progress-group">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Suspendidas</span>
                                        <span><?php echo $stats['suspendidas']; ?></span>
                                    </div>
                                    <div class="progress mb-3">
                                        <div class="progress-bar bg-warning" style="width: <?php echo $stats['licencias'] > 0 ? ($stats['suspendidas'] / $stats['licencias'] * 100) : 0; ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Acciones Rápidas</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="licencias.php" class="btn btn-primary">
                                        <i class="fas fa-key me-2"></i>
                                        Ver Mis Licencias
                                    </a>
                                    <a href="clientes.php" class="btn btn-info">
                                        <i class="fas fa-user-plus me-2"></i>
                                        Gestionar Clientes
                                    </a>
                                    <a href="https://wa.me/5491161396633" class="btn btn-success" target="_blank">
                                        <i class="fab fa-whatsapp me-2"></i>
                                        Soporte WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>