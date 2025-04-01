<?php
session_start();
require_once('classes/Database.php');

class Order {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createOrder($user_id, $cart_items) {
        $total_price = 0;
        foreach ($cart_items as $item_id) {
            $stmt = $this->pdo->prepare("SELECT price FROM products WHERE id = ?");
            $stmt->execute([$item_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($product) {
                $total_price += $product['price'];
            }
        }

        $stmt = $this->pdo->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
        $stmt->execute([$user_id, $total_price]);
        return $this->pdo->lastInsertId();
    }

    public function addOrderItems($order_id, $cart_items) {
        foreach ($cart_items as $item_id) {
            $stmt = $this->pdo->prepare("SELECT price FROM products WHERE id = ?");
            $stmt->execute([$item_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($product) {
                $stmt = $this->pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt->execute([$order_id, $item_id, 1, $product['price']]);
            }
        }
    }

    public function updateOrderStatus($order_id) {
        $stmt = $this->pdo->prepare("UPDATE orders SET status = 1 WHERE id = ?");
        $stmt->execute([$order_id]);
        return $stmt->rowCount() > 0;
    }
}

$pdo = Database::getInstance()->getConnection();
$orderObj = new Order($pdo);

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $cart_items = $_SESSION['cart'];
    $user_id = 1;
    $order_id = $orderObj->createOrder($user_id, $cart_items);
    $orderObj->addOrderItems($order_id, $cart_items);
    unset($_SESSION['cart']);
    
    $stripe_url = 'https://buy.stripe.com/test_CN214C0B93gM6Vc3cc';
    header("Location: $stripe_url?order_id=$order_id");
    exit;
} elseif (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    if ($orderObj->updateOrderStatus($order_id)) {
        header("Location: order_history.php");
        exit;
    } else {
        echo "Aucune commande trouvée pour cet ID.";
    }
} else {
    echo "Le panier est vide ou ID de commande manquant.";
}
?>