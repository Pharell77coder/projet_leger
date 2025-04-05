<?php
session_start();
require_once('classes/Database.php');
require 'classes/Order.php';

$pdo = Database::getInstance()->getConnection();
$orderObj = new Order($pdo);

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $cart_items = $_SESSION['cart'];
    $user_id = 1;
    $order_id = $orderObj->createOrder($user_id, $cart_items);
    $orderObj->addOrderItems($order_id, $cart_items);
    unset($_SESSION['cart']);
    
    //$stripe_url = 'https://buy.stripe.com/test_CN214C0B93gM6Vc3cc';
    $stripe_url = 'checkout.php';
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