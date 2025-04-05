<?php
session_start();
require 'classes/Cart.php';

$cart = new Cart();

if (isset($_POST['add_to_cart']) && !empty($_POST['product_id'])) {
    $cart->addToCart($_POST['product_id']);
    header("Location: ../index.php");
    exit();
} else {
    header("Location: ../index.php");
    exit();
}
?>
