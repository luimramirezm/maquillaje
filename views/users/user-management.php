<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require_once '../../config/db.php';
require_once '../../models/User.php';

$db = new Db();
$conn = $db->getConnection();

$userModel = new User($conn);
$usuarios = $userModel->getAllUsersWithRoles();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios - GlamStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-4">

        <!-- Navegación -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="../dashboard.php" class="btn btn-outline-secondary rounded-pill btn-sm">
                ← Volver al dashboard
            </a>
            <a href="../logout.php" class="btn btn-outline-danger rounded-pill btn-sm">
                Cerrar sesión
            </a>
        </div>

        <!-- Encabezado -->
        <div class="d-flex align-items-center gap-3 mb-4">
            <h2 class="text-danger m-0">Gestión de Usuarios</h2>
        </div>
        <a href="create-user.php" class="btn btn-danger rounded-pill mb-3">
            + Crear nuevo usuario
        </a>

        <!-- Tabla de usuarios -->
        <div class="card shadow-sm border-0">

            <div class="card-body">
                <table class="table table-striped table-hover align-middle">
                    <thead style="background-color:rgb(73, 69, 70);" class="text-white">
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['role_name']) ?></td>
                            <td class="d-flex gap-2">
                                <a href="../../controllers/UserController.php?edit=<?= $user['id'] ?>"
                                    class="btn btn-outline-secondary rounded-pill btn-sm">Editar</a>


                                <form method="POST" action="../../controllers/UserController.php"
                                    onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?');"
                                    class="d-inline-block">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <button name="delete"
                                        class="btn btn-outline-danger btn-sm rounded-pill">Eliminar</button>
                                </form>

                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>


</body>

</html>