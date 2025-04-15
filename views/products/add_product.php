<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once '../../config/db.php';
require_once '../../models/Category.php';

$db = new Db();
$conn = $db->getConnection();
$categoryModel = new Category($conn);
$categorias = $categoryModel->getAll();
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="mb-3 text-end">
                    <a href="index.php" class="btn btn-outline-secondary rounded-pill">← Volver al listado</a>
                </div>
                <h3 class="mb-4 text-center text-danger">Agregar Producto 💄</h3>
                <form action="../../controllers/ProductController.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="add">

                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control" name="description" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Precio</label>
                        <input type="number" class="form-control" name="price" step="0.01" required>
                    </div>

                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoría</label>
                        <select name="category_id" id="categoria" class="form-select" required>
                            <option value="" disabled selected>Selecciona una categoría</option>
                            <?php foreach ($categorias as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>


                    <div class="mb-3">
                        <label for="image" class="form-label">Imagen</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                    </div>

                    <button type="submit" class="btn btn-danger w-100">Guardar</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>