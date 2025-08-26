<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'revendedor') {
    header('Location: ../login.php');
    exit;
}

require_once '../config/database.php';

$userId = $_SESSION['user_id'];
$message = '';
$messageType = '';

// Crear cliente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'create') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $empresa = trim($_POST['empresa']);
    $licencia_id = (int)$_POST['licencia_id'];
    
    if (empty($nombre) || empty($email) || empty($licencia_id)) {
        $message = 'Nombre, email y licencia son obligatorios.';
        $messageType = 'danger';
    } else {
        // Verificar que la licencia pertenezca al revendedor
        $stmt = $pdo->prepare("SELECT id FROM licencias WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$licencia_id, $userId]);
        if (!$stmt->fetch()) {
            $message = 'La licencia seleccionada no es válida.';
            $messageType = 'danger';
        } else {
            $stmt = $pdo->prepare("INSERT INTO clientes_finales (nombre, email, telefono, empresa, licencia_id) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$nombre, $email, $telefono, $empresa, $licencia_id])) {
                $message = 'Cliente creado exitosamente.';
                $messageType = 'success';
            } else {
                $message = 'Error al crear el cliente.';
                $messageType = 'danger';
            }
        }
    }
}

// Eliminar cliente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $id = (int)$_POST['id'];
    // Verificar que el cliente pertenezca al revendedor
    $stmt = $pdo->prepare("SELECT cf.id FROM clientes_finales cf 
                          JOIN licencias l ON cf.licencia_id = l.id 
                          WHERE cf.id = ? AND l.usuario_id = ?");
    $stmt->execute([$id, $userId]);
    if (!$stmt->fetch()) {
        $message = 'No tienes permiso para eliminar este cliente.';
        $messageType = 'danger';
    } else {
        $stmt = $pdo->prepare("DELETE FROM clientes_finales WHERE id = ?");
        if ($stmt->execute([$id])) {
            $message = 'Cliente eliminado exitosamente.';
            $messageType = 'success';
        } else {
            $message = 'Error al eliminar el cliente.';
            $messageType = 'danger';
        }
    }
}

// Obtener clientes del revendedor
$stmt = $pdo->prepare("SELECT cf.*, l.nombre as licencia_nombre 
                       FROM clientes_finales cf 
                       JOIN licencias l ON cf.licencia_id = l.id 
                       WHERE l.usuario_id = ? 
                       ORDER BY cf.fecha_registro DESC");
$stmt->execute([$userId]);
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener licencias del revendedor para el select
$stmt = $pdo->prepare("SELECT id, nombre FROM licencias WHERE usuario_id = ? AND estado = 'activa' ORDER BY nombre");
$stmt->execute([$userId]);
$licencias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Clientes - SoftwarePar</title>
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
                    <a class="nav-link" href="licencias.php">
                        <i class="fas fa-key"></i> Mis Licencias
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="clientes.php">
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
                            <h1>Mis Clientes</h1>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClienteModal">
                                <i class="fas fa-plus me-2"></i>
                                Agregar Cliente
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

                <!-- Clientes Table -->
                <div class="card">
                    <div class="card-header">
                        <h5>Lista de Mis Clientes</h5>
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
                                        <th>Fecha Registro</th>
                                        <th>Acciones</th>
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
                                            <td><?php echo date('d/m/Y', strtotime($cliente['fecha_registro'])); ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-danger" onclick="deleteCliente(<?php echo $cliente['id']; ?>)">
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

    <!-- Add Cliente Modal -->
    <div class="modal fade" id="addClienteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Cliente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="create">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" name="telefono">
                        </div>
                        <div class="mb-3">
                            <label for="empresa" class="form-label">Empresa</label>
                            <input type="text" class="form-control" name="empresa">
                        </div>
                        <div class="mb-3">
                            <label for="licencia_id" class="form-label">Licencia</label>
                            <select class="form-control" name="licencia_id" required>
                                <option value="">Seleccionar licencia</option>
                                <?php foreach ($licencias as $licencia): ?>
                                    <option value="<?php echo $licencia['id']; ?>"><?php echo htmlspecialchars($licencia['nombre']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear Cliente</button>
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
        function deleteCliente(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este cliente?')) {
                document.getElementById('deleteId').value = id;
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
</body>
</html>