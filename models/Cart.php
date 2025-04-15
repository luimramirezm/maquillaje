<?php
class Cart {
    public function __construct() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

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

    public function remove($productId) {
        unset($_SESSION['cart'][$productId]);
    }

    public function clear() {
        unset($_SESSION['cart']);
    }

    public function getItems() {
        return $_SESSION['cart'] ?? [];
    }

    public function update($productId, $quantity) {
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$productId]); 
        } else {
            $_SESSION['cart'][$productId] = $quantity; 
        }
    }
    
}
?>