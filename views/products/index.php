<?php
session_start();

// Redirige si no está logueado
if (!isset($_SESSION['username'])) {
    header("Location: ../loging.php");
    exit;
}

// Incluir modelo
require_once '../../models/Product.php';
require_once '../../config/db.php';



// Obtener productos
$db = new Db();
$conn = $db->getConnection();
$product = new Product($conn);
$productos = $product->getAll();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Productos - GlamStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="../dashboard.php" class="btn btn-outline-secondary rounded-pill btn-sm">
                ← Volver al dashboard
            </a>

            <a href="../logout.php" class="btn btn-outline-danger rounded-pill btn-sm">
                Cerrar sesión
            </a>
        </div>

        <div class="d-flex align-items-center gap-3 mb-4">

            <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="add_product.php" class="btn btn-danger btn-sm rounded-pill">+ Agregar producto</a>
            <?php endif; ?>
            <h2 class="text-danger m-0"> Productos 💄</h2>
        </div>

        <div class="row">
            <?php foreach ($productos as $p): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <img src="../../uploads/<?= htmlspecialchars($p['image']) ?>" class="card-img-top bg-light"
                        alt="<?= htmlspecialchars($p['name']) ?>" style="height: 200px; object-fit: contain;">


                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($p['name']) ?></h5>
                        <p class="card-text flex-grow-1"><?= htmlspecialchars($p['description']) ?></p>
                        <p class="text-danger fw-bold">$<?= number_format($p['price'], 0, ',', '.') ?></p>

                        <?php if ($_SESSION['role'] === 'admin'): ?>
                        <div class="d-flex gap-2 mt-auto">
                            <a href="edit.php?id=<?= $p['id'] ?>"
                                class="btn btn-outline-secondary btn-sm w-100">Editar</a>
                            <a href="../../controllers/ProductController.php?action=delete&id=<?= $p['id'] ?>"
                                class="btn btn-outline-danger btn-sm w-100"
                                onclick="return confirm('¿Seguro que deseas eliminar este producto?');">
                                Eliminar
                            </a>
                        </div>
                        <?php else: ?>
                        <button class="btn btn-outline-success btn-sm mt-auto w-100">Agregar al carrito</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
       

</body>

</html>