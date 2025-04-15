<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Maquillaje.com</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">

      <div class="card border-0 shadow-lg rounded-4">
        <div class="card-body p-4">
          <h4 class="card-title text-center mb-4 text-danger-emphasis">Bienvenida de nuevo 💋</h4>

          <?php if (isset($_SESSION['login_error'])): ?>
            <div class="alert alert-danger">
              <?= $_SESSION['login_error']; unset($_SESSION['login_error']); ?>
            </div>
          <?php endif; ?>

          <form action="../controllers/AuthController.php" method="POST">
            <input type="hidden" name="action" value="login">

            <div class="mb-3">
              <label for="username" class="form-label text-secondary">Nombre de usuario</label>
              <input type="text" class="form-control rounded-3" id="username" name="username" required>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label text-secondary">Contraseña</label>
              <input type="password" class="form-control rounded-3" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-danger w-100 rounded-pill">Iniciar sesión</button>
          </form>

          <div class="text-center mt-3">
            <a href="register.php" class="link-danger text-decoration-none">¿No tienes cuenta? Regístrate</a>
          </div>

        </div>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
