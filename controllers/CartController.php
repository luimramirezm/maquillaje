<?php
session_start();
require_once '../models/Cart.php';

$cart = new Cart();


   //Agregar producto al carrito (desde dashboard)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id']) && !isset($_POST['action'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    $cart->add($product_id, $quantity);

    // Mensaje de confirmación
    $_SESSION['success_message'] = '✅ ¡Producto agregado al carrito!';

    header("Location: ../views/dashboard.php");
    exit;
}

    //Vaciar todo el carrito 
if (isset($_GET['action']) && $_GET['action'] === 'clear') {
    $cart->clear();
    header("Location: ../views/cart.php");
    exit;
}


   //Eliminar un solo producto del carrito (botón Eliminar)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'remove') {
    $product_id = $_POST['product_id'];
    $cart->remove($product_id);
    $_SESSION['success_message'] = '🗑️ Producto eliminado del carrito.';
    header("Location: ../views/cart.php");
    exit;
}


// Actualizar cantidad de un producto en el carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update') {
    $product_id = $_POST['product_id'];
    $quantity = (int) $_POST['quantity'];
    $cart->update($product_id, $quantity);
    $_SESSION['success_message'] = '🔁 Cantidad actualizada.';
    header("Location: ../views/cart.php");
    exit;
}
?>