<?php
class Cart {
    // Constructor que inicializa el carrito si no existe en la sesión
    public function __construct() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    // Agrega un producto al carrito. Si ya existe, incrementa su cantidad.
    public function add($product_id, $quantity = 1) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    }

    // Elimina un producto específico del carrito usando su ID
    public function remove($productId) {
        unset($_SESSION['cart'][$productId]);
    }

    // Vacía por completo el carrito eliminando la sesión
    public function clear() {
        unset($_SESSION['cart']);
    }

    // Devuelve todos los productos del carrito como un array
    public function getItems() {
        return $_SESSION['cart'] ?? [];
    }

        // Actualiza la cantidad de un producto en el carrito. 
    // Si la cantidad es 0 o menor, lo elimina.
    public function update($productId, $quantity) {
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$productId]); 
        } else {
            $_SESSION['cart'][$productId] = $quantity; 
        }
    }
    
}
?>