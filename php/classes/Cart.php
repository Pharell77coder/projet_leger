<?php
class Cart {
    public static function addToCart($product_id) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $_SESSION['cart'][] = $product_id;
    }

    public static function getCartItems() {
        return $_SESSION['cart'] ?? [];
    }

    public static function clearCart() {
        $_SESSION['cart'] = [];
    }
}
?>
