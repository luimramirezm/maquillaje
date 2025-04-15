<?php
// Inicia sesión
session_start();

// Requiere archivos necesarios para la conexión y modelo de productos
require_once '../config/db.php';
require_once '../models/Category.php';
require_once '../models/Product.php';

// Crea instancia de la base de datos y obtiene conexión
$db = new Db();
$conn = $db->getConnection();

//  Carga el modelo de productos
$productModel = new Product($conn);

// Carga el modelo de categorías
$categoryModel = new Category($conn);
$categorias = $categoryModel->getAll();

// Filtra productos si se selecciona una categoría
if (isset($_GET['category']) && $_GET['category'] !== '') {
  $productos = $productModel->getByCategory($_GET['category']);
} else {
  $productos = $productModel->getAll();
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <!-- Bootstrap para estilos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Contenedor principal con padding -->
    <div class="container py-4">

        <?php if (isset($_SESSION['success_message'])): ?>
        <!-- Modal de éxito (producto agregado) -->
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0">
                    <div class="modal-header bg-white text-danger border-bottom rounded-top-4">
                        <h5 class="modal-title" id="successModalLabel">¡Producto agregado!</h5>
                    </div>
                    <div class="modal-body text-center text-dark">
                        <span class="fs-4">💄</span> <?= $_SESSION['success_message'] ?>
                    </div>
                    <div class="modal-footer justify-content-center border-top-0">
                        <button type="button" class="btn btn-danger rounded-pill px-4"
                            data-bs-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Script para mostrar automáticamente el modal si hay mensaje -->
        <script>
        window.addEventListener('DOMContentLoaded', () => {
            const myModal = new bootstrap.Modal(document.getElementById('successModal'));
            myModal.show();
        });
        </script>

        <!-- Elimina el mensaje para que no se repita -->
        <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <!-- Bienvenida al usuario y botones según sesión -->
        <?php if (isset($_SESSION['username'])): ?>
        <!-- Encabezado con saludo, carrito y botón de cierre de sesión -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>¡Hola, <?= $_SESSION['username'] ?>! 💋</h2>

            <div class="d-flex align-items-center gap-2 flex-wrap">

                <!-- Filtro de categorías -->
                <form action="" method="GET" class="d-flex align-items-center">
                    <select name="category" class="form-select form-select-sm rounded-pill me-2"
                        onchange="this.form.submit()">
                        <option value="">Todas las categorías</option>
                        <?php foreach ($categorias as $cat): 
            $selected = (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'selected' : '';
          ?>
                        <option value="<?= $cat['id'] ?>" <?= $selected ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </form>
                <!-- Botón del carrito -->
                <a href="cart.php" class="btn btn-outline-secondary rounded-pill d-flex align-items-center gap-2">
                    🛒 <span class="d-none d-sm-inline">Carrito</span>
                </a>
                <!-- Botón de cerrar sesión -->
                <a href="logout.php" class="btn btn-outline-danger rounded-pill">Cerrar sesión</a>
            </div>
        </div>


        <!-- Botón de gestión de productos solo para admin -->
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <a href="products/index.php" class="btn btn-outline-danger rounded-pill mb-4">Gestionar productos</a>
        <a href="users/user-management.php" class="btn btn-outline-danger rounded-pill mb-4">Gestor de usuarios</a>
        <?php endif; ?>



        <?php else: ?>
        <!-- Si no ha iniciado sesión -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-danger">Maquillaje.com 💄</h2>
            <!-- 🔍 Filtro de categorías (SIEMPRE VISIBLE) -->
            <form action="" method="GET" class="d-flex align-items-center">
                <select name="category" class="form-select form-select-sm rounded-pill me-2"
                    onchange="this.form.submit()">
                    <option value="">Todas las categorías</option>
                    <?php foreach ($categorias as $cat): 
      $selected = (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'selected' : '';
    ?>
                    <option value="<?= $cat['id'] ?>" <?= $selected ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </form>
            <a href="login.php" class="btn btn-outline-danger rounded-pill">Iniciar sesión</a>
        </div>
        <?php endif; ?>

        <!-- Lista de productos -->
        <div class="row">

            <?php foreach ($productos as $p): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow rounded-4 h-100 d-flex flex-column">

                    <!-- Imagen del producto -->
                    <?php if (!empty($p['image'])): ?>
                    <img src="../uploads/<?= $p['image'] ?>" class="card-img-top bg-light rounded-top"
                        style="height: 200px; object-fit: contain; cursor: pointer;" data-bs-toggle="modal"
                        data-bs-target="#imageModal"
                        onclick="document.getElementById('modalImage').src='../uploads/<?= $p['image'] ?>'">

                    <?php endif; ?>

                    <!-- Información del producto -->
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title text-danger"><?= htmlspecialchars($p['name']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($p['description']) ?></p>
                        </div>

                        <!-- Precio y botón agregar al carrito -->
                        <div>
                            <p class="text-muted fw-bold">$<?= number_format($p['price'], 0, ',', '.') ?></p>

                            <?php if (isset($_SESSION['username'])): ?>
                            <!-- Formulario para agregar al carrito -->
                            <form action="../controllers/CartController.php" method="POST" class="d-flex gap-2">
                                <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                                <input type="number" name="quantity" min="1" value="1"
                                    class="form-control form-control-sm w-25 rounded-pill">
                                <button type="submit" class="btn btn-danger btn-sm rounded-pill w-100">Agregar</button>
                            </form>
                            <?php else: ?>
                            <!-- Si no está logueado redirige a login -->
                            <a href="login.php" class="btn btn-danger w-100 rounded-pill">Agregar al carrito</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>

    <!-- Modal para mostrar la imagen en grande -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" class="img-fluid rounded-4"
                        style="max-height: 80vh; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>


    <!--  Scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>