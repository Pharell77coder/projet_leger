<?php
/*
session_start();

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $_SESSION['cart'][] = $product_id;

    header("Location: cart.php");
    exit;
} else {
    header("Location: ../index.php");
    exit;
}*/

session_start();
require 'classes/Cart.php';

if (isset($_POST['add_to_cart']) && !empty($_POST['product_id'])) {
    Cart::addToCart($_POST['product_id']);
    header("Location: cart.php");
    exit();
} else {
    header("Location: ../index.php");
    exit();
}
?>
