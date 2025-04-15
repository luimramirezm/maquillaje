<?php
session_start();
require_once '../models/Cart.php';
require_once '../models/Product.php';
require_once '../config/db.php';

// Si no hay sesión, redirigir
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$cart = new Cart();
$items = $cart->getItems();

$db = new Db();
$conn = $db->getConnection();
$productModel = new Product($conn);

$total = 0;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-danger">🛒 Tu carrito</h2>
            <div class="d-flex gap-2">
                <a href="dashboard.php" class="btn btn-outline-secondary rounded-pill">← Volver</a>
                <a href="../controllers/CartController.php?action=clear"
                    class="btn btn-outline-danger rounded-pill">Vaciar carrito</a>
            </div>
        </div>

        <?php if (empty($items)): ?>
        <div class="alert alert-warning text-center">Tu carrito está vacío.</div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Imagen</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $id => $qty): 
    $product = $productModel->getById($id);
    $subtotal = $product['price'] * $qty;
    $total += $subtotal;
  ?>
                    <tr>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td>
                            <img src="../uploads/<?= $product['image'] ?>" width="60" style="object-fit:contain;">
                        </td>
                        <td class="p-0">
                            <form action="../controllers/CartController.php" method="POST"
                                class="d-flex align-items-center">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="product_id" value="<?= $id ?>">
                                <input type="number" name="quantity" value="<?= $qty ?>" min="1"
                                    class="form-control form-control-sm rounded-pill me-1" style="width: 55px;">
                                <button type="submit" class="btn btn-outline-secondary btn-sm rounded-pill">↺</button>
                            </form>
                        </td>
                        <td class="text-nowrap">$<?= number_format($product['price'], 0, ',', '.') ?></td>
                        <td class="fw-bold text-nowrap">$<?= number_format($subtotal, 0, ',', '.') ?></td>
                        <td>
                            <form action="../controllers/CartController.php" method="POST">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="product_id" value="<?= $id ?>">
                                <button type="submit"
                                    class="btn btn-sm btn-outline-danger rounded-pill">Eliminar</button>
                            </form>
                        </td>
                    </tr>

                    <?php endforeach; ?>
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end fw-bold">Total:</td>
                        <td class="fw-bold text-danger">$<?= number_format($total, 0, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php endif; ?>
    </div>
</body>

</html>