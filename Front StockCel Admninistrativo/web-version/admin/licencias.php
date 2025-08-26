<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

require_once '../config/database.php';

$message = '';
$messageType = '';

// Crear licencia
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'create') {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $usuario_id = (int)$_POST['usuario_id'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_vencimiento = $_POST['fecha_vencimiento'];
    $precio = (float)$_POST['precio'];
    $estado = $_POST['estado'];
    
    if (empty($nombre) || empty($usuario_id) || empty($fecha_inicio) || empty($fecha_vencimiento)) {
        $message = 'Todos los campos obligatorios deben ser completados.';
        $messageType = 'danger';
    } else {
        $stmt = $pdo->prepare("INSERT INTO licencias (nombre, descripcion, usuario_id, fecha_inicio, fecha_vencimiento, precio, estado) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$nombre, $descripcion, $usuario_id, $fecha_inicio, $fecha_vencimiento, $precio, $estado])) {
            $message = 'Licencia creada exitosamente.';
            $messageType = 'success';
        } else {
            $message = 'Error al crear la licencia.';
            $messageType = 'danger';
        }
    }
}

// Eliminar licencia
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $id = (int)$_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM licencias WHERE id = ?");
    if ($stmt->execute([$id])) {
        $message = 'Licencia eliminada exitosamente.';
        $messageType = 'success';
    } else {
        $message = 'Error al eliminar la licencia.';
        $messageType = 'danger';
    }
}

// Obtener licencias con información del revendedor
$stmt = $pdo->prepare("SELECT l.*, u.nombre as revendedor_nombre 
                       FROM licencias l 
                       JOIN usuarios u ON l.usuario_id = u.id 
                       ORDER BY l.fecha_creacion DESC");
$stmt->execute();
$licencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener revendedores para el select
$stmt = $pdo->prepare("SELECT id, nombre FROM usuarios WHERE rol = 'revendedor' ORDER BY nombre");
$stmt->execute();
$revendedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Licencias - SoftwarePar</title>
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
                    <a class="nav-link active" href="licencias.php">
                        <i class="fas fa-key"></i> Licencias
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="clientes.php">
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
                            <h1>Gestión de Licencias</h1>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLicenciaModal">
                                <i class="fas fa-plus me-2"></i>
                                Agregar Licencia
                            </button>
                        </div>
                    </div>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Licencias Table -->
                <div class="card">
                    <div class="card-header">
                        <h5>Lista de Licencias</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Revendedor</th>
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
                                            <td><?php echo htmlspecialchars($licencia['revendedor_nombre']); ?></td>
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
                                                <button class="btn btn-sm btn-danger" onclick="deleteLicencia(<?php echo $licencia['id']; ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
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

    <!-- Add Licencia Modal -->
    <div class="modal fade" id="addLicenciaModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Licencia</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="create">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" name="nombre" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="usuario_id" class="form-label">Revendedor</label>
                                    <select class="form-control" name="usuario_id" required>
                                        <option value="">Seleccionar revendedor</option>
                                        <?php foreach ($revendedores as $revendedor): ?>
                                            <option value="<?php echo $revendedor['id']; ?>"><?php echo htmlspecialchars($revendedor['nombre']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" name="descripcion" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                                    <input type="date" class="form-control" name="fecha_inicio" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="fecha_vencimiento" class="form-label">Fecha Vencimiento</label>
                                    <input type="date" class="form-control" name="fecha_vencimiento" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="precio" class="form-label">Precio</label>
                                    <input type="number" step="0.01" class="form-control" name="precio" min="0">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-control" name="estado" required>
                                <option value="activa">Activa</option>
                                <option value="vencida">Vencida</option>
                                <option value="suspendida">Suspendida</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear Licencia</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Form -->
    <form id="deleteForm" method="POST" style="display: none;">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id" id="deleteId">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteLicencia(id) {
            if (confirm('¿Estás seguro de que deseas eliminar esta licencia?')) {
                document.getElementById('deleteId').value = id;
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
</body>
</html>