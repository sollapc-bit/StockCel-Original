<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

require_once '../config/database.php';

// Obtener clientes con información de licencia y revendedor
$stmt = $pdo->prepare("SELECT cf.*, l.nombre as licencia_nombre, u.nombre as revendedor_nombre 
                       FROM clientes_finales cf 
                       JOIN licencias l ON cf.licencia_id = l.id 
                       JOIN usuarios u ON l.usuario_id = u.id 
                       ORDER BY cf.fecha_registro DESC");
$stmt->execute();
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - SoftwarePar</title>
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
                <h4>Admin Panel</h4>
            </div>
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="revendedores.php">
                        <i class="fas fa-users"></i> Revendedores
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="licencias.php">
                        <i class="fas fa-key"></i> Licencias
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="clientes.php">
                        <i class="fas fa-user-friends"></i> Clientes
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
                            <h1>Gestión de Clientes Finales</h1>
                        </div>
                    </div>
                </div>

                <!-- Clientes Table -->
                <div class="card">
                    <div class="card-header">
                        <h5>Lista de Clientes Finales</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                        <th>Empresa</th>
                                        <th>Licencia</th>
                                        <th>Revendedor</th>
                                        <th>Fecha Registro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($clientes as $cliente): ?>
                                        <tr>
                                            <td><?php echo $cliente['id']; ?></td>
                                            <td><?php echo htmlspecialchars($cliente['nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                                            <td><?php echo htmlspecialchars($cliente['telefono']); ?></td>
                                            <td><?php echo htmlspecialchars($cliente['empresa']); ?></td>
                                            <td><?php echo htmlspecialchars($cliente['licencia_nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($cliente['revendedor_nombre']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($cliente['fecha_registro'])); ?></td>
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