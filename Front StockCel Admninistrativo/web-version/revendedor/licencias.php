<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'revendedor') {
    header('Location: ../login.php');
    exit;
}

require_once '../config/database.php';

$userId = $_SESSION['user_id'];

// Obtener licencias del revendedor
$stmt = $pdo->prepare("SELECT * FROM licencias WHERE usuario_id = ? ORDER BY fecha_creacion DESC");
$stmt->execute([$userId]);
$licencias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Licencias - SoftwarePar</title>
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
                    <a class="nav-link" href="dashboard.php">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="licencias.php">
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
                            <h1>Mis Licencias</h1>
                        </div>
                    </div>
                </div>

                <!-- Licencias Table -->
                <div class="card">
                    <div class="card-header">
                        <h5>Lista de Mis Licencias</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Precio</th>
                                        <th>Inicio</th>
                                        <th>Vencimiento</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($licencias as $licencia): ?>
                                        <tr>
                                            <td><?php echo $licencia['id']; ?></td>
                                            <td><?php echo htmlspecialchars($licencia['nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($licencia['descripcion']); ?></td>
                                            <td>$<?php echo number_format($licencia['precio'], 2); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($licencia['fecha_inicio'])); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($licencia['fecha_vencimiento'])); ?></td>
                                            <td>
                                                <?php
                                                $badgeClass = '';
                                                switch ($licencia['estado']) {
                                                    case 'activa':
                                                        $badgeClass = 'bg-success';
                                                        break;
                                                    case 'vencida':
                                                        $badgeClass = 'bg-danger';
                                                        break;
                                                    case 'suspendida':
                                                        $badgeClass = 'bg-warning';
                                                        break;
                                                }
                                                ?>
                                                <span class="badge <?php echo $badgeClass; ?>"><?php echo ucfirst($licencia['estado']); ?></span>
                                            </td>
                                            <td>
                                                <a href="clientes.php?licencia_id=<?php echo $licencia['id']; ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-users"></i> Ver Clientes
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>