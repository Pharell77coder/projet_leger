<?php

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
