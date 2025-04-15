<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5">
        <a href="user-management.php" class="btn btn-outline-secondary rounded-pill mb-4">← Volver</a>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h3 class="mb-4 text-danger">Crear Nuevo Usuario</h3>

                <form method="POST" action="../../controllers/UserController.php">
                    <div class="mb-3">
                        <label for="username" class="form-label">Nombre de usuario</label>
                        <input type="text" class="form-control" name="username" value="" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" name="email" value="" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" name="password" value="" required>
                    </div>

                    <div class="mb-3">
                        <label for="role_id" class="form-label">Rol</label>
                        <select name="role_id" class="form-select" required>
                            <option value="1">Admin</option>
                            <option value="2" selected>Cliente</option>
                        </select>
                    </div>

                    <button type="submit" name="create" class="btn btn-danger rounded-pill">Crear Usuario</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>