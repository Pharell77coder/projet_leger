<?php
require_once 'Database.php';

class Cart {
    private static $conn;

    public static function init() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if (self::$conn === null) {
            self::$conn = Database::getInstance()->getConnection();
        }
    }

    public static function addToCart($product_id) {
        self::init();
        if (!isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] = 1;
        } else {
            $_SESSION['cart'][$product_id]++;
        }
    }

    public static function removeFromCart($product_id) {
        self::init();
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]--;
            if ($_SESSION['cart'][$product_id] <= 0) {
                unset($_SESSION['cart'][$product_id]);
            }
        }
    }

    public static function removeItem($product_id) {
        self::init();
        unset($_SESSION['cart'][$product_id]);
    }

    public static function clearCart() {
        self::init();
        $_SESSION['cart'] = [];
    }

    public static function getCartItems() {
        self::init();
        return $_SESSION['cart'];
    }
    public static function getTotal() {
        self::init();
        $total = 0;

        if (!empty($_SESSION['cart'])) {
            $stmt = self::$conn->prepare("SELECT id, price FROM products WHERE id IN (" . implode(',', array_keys($_SESSION['cart'])) . ")");
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($products as $product) {
                $product_id = $product['id'];
                $price = (float)$product['price'];
                $quantity = $_SESSION['cart'][$product_id] ?? 0;
                $total += $price * $quantity;
            }
        }

        return $total;
    }
}
?>
