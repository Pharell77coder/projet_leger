<?php
session_start();

require_once 'classes/Database.php';

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if (empty($cart_items)) {
    header("Location: cart.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['username'])) {
        echo "Utilisateur non connecté.";
        exit();
    }

    $payment_method = $_POST['payment_method'];
    $address = $_POST['address']; // simulée uniquement

    try {
        $pdo = Database::getInstance()->getConnection();

        // Récupération de l'utilisateur
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$_SESSION['username']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            echo "Utilisateur introuvable.";
            exit();
        }
        $user_id = $user['id'];

        // Calcul du total
        $total_price = 0;
        foreach ($_SESSION['cart'] as $product_id => $item) {
            $total_price += $item['price'] * $item['quantity'];
        }

        // Insertion de la commande
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, payment_method, total_price, status, order_date, email_order) VALUES (?, ?, ?, 0, NOW(), 0)");
        $stmt->execute([$user_id, $payment_method, $total_price]);
        $order_id = $pdo->lastInsertId();

        // Insertion des produits dans order_items
        foreach ($_SESSION['cart'] as $product_id => $item) {
            $quantity = $item['quantity'];
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$order_id, $product_id, $quantity]);
        }

        // On vide le panier
        unset($_SESSION['cart']);

        // Redirection vers la confirmation
        header("Location: payement_confirmation.php?order_id=$order_id");
        exit();

    } catch (PDOException $e) {
        echo "Erreur lors de la création de la commande : " . $e->getMessage();
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalisation de l'Achat</title>
    <link rel="stylesheet" href="../css/global.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <h1>Finalisation de l'Achat</h1>

    <?php if (!empty($cart_items)) : ?>
        <p>Vous avez <?php echo count($cart_items); ?> article(s) dans votre panier.</p>

        <form method="post" action="">
            <label for="address">Adresse de livraison :</label><br>
            <input type="text" id="address" name="address" required><br>

            <label for="payment">Méthode de paiement :</label><br>
            <select id="payment" name="payment_method">
                <option value="credit_card">Carte de Crédit</option>
                <option value="paypal">PayPal</option>
            </select><br>

            <button type="submit">Finaliser l'Achat</button>
        </form>
    <?php else : ?>
        <p>Votre panier est vide.</p>
    <?php endif; ?>

    <?php include 'footer.php'; ?>
</body>
</html>
