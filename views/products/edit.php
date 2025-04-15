<?php
session_start();
require_once '../../config/db.php';
require_once '../../models/Product.php';
require_once '../../models/Category.php';

// Validación básica
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$db = new Db();
$conn = $db->getConnection();
$productModel = new Product($conn);
$categoryModel = new Category($conn);

$product = $productModel->getById($_GET['id']);
$categorias = $categoryModel->getAll();

if (!$product) {
    $_SESSION['message'] = "Producto no encontrado.";
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <h2 class="text-danger">✏️ Editar producto</h2>
            <a href="index.php" class="btn btn-outline-danger rounded-pill">← Volver</a>
        </div>

        <form action="../../controllers/ProductController.php" method="POST" enctype="multipart/form-data"
            class="card shadow p-4 rounded-4">
            <input type="hidden" name="id" value="<?= $product['id'] ?>">
            <input type="hidden" name="action" value="update">

            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control rounded-pill" name="name"
                    value="<?= htmlspecialchars($product['name']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <textarea class="form-control rounded-4" name="description" rows="3"
                    required><?= htmlspecialchars($product['description']) ?></textarea>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Precio</label>
                <input type="number" class="form-control rounded-pill" name="price" value="<?= $product['price'] ?>"
                    required>
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Categoría</label>
                <select name="category_id" class="form-select rounded-pill" required>
                    <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $product['category_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Imagen (opcional)</label>
                <input type="file" class="form-control rounded-pill" name="image" accept="image/*">
                <?php if (!empty($product['image'])): ?>
                <div class="mt-2">
                    <img src="../../uploads/<?= $product['image'] ?>" width="100" class="rounded shadow-sm">
                </div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-danger rounded-pill px-4">Guardar cambios</button>
        </form>
    </div>

</body>

</html>