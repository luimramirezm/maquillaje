<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5">

        <!-- Navegación -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="user-management.php" class="btn btn-outline-secondary rounded-pill mb-4">← Volver</a>
            <a href="../../../logout.php" class="btn btn-outline-danger rounded-pill btn-sm">
                Cerrar sesión
            </a>
        </div>


        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h3 class="mb-4 text-danger">Editar Usuario</h3>

                <form method="POST" action="/maquillaje/controllers/UserController.php">

                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($usuario['id']) ?>">

                    <div class="mb-3">
                        <label for="username" class="form-label">Nombre de usuario</label>
                        <input type="text" class="form-control" name="username"
                            value="<?= htmlspecialchars($usuario['username']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" name="email"
                            value="<?= htmlspecialchars($usuario['email']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="role_id" class="form-label">Rol</label>
                        <select name="role_id" class="form-select" required>
                            <option value="1" <?= $usuario['role_id'] == 1 ? 'selected' : '' ?>>Admin</option>
                            <option value="2" <?= $usuario['role_id'] == 2 ? 'selected' : '' ?>>Cliente</option>
                        </select>
                    </div>

                    <button type="submit" name="update" class="btn btn-danger rounded-pill">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>