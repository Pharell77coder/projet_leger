<?php
session_start();
include 'bdd.php';
// Vérifier si le panier contient des produits
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    // Connexion à la bdd avec gestion des erreurs
    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Calculer le total du panier
        $total_price = 0;
        $cart_items = $_SESSION['cart'];
        foreach ($cart_items as $item_id) {
            $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
            $stmt->execute([$item_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($product) {
                $total_price += $product['price'];
            }
        }

        $user_id = 1;

        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
        $stmt->execute([$user_id, $total_price]);

        $order_id = $pdo->lastInsertId();

    // Insérer les éléments du panier dans la table `order_items`
    foreach ($cart_items as $item_id) {
        // Récupérer les informations du produit
        $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
        $stmt->execute([$item_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($product) {
            // Insérer l'article dans la table `order_items`
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$order_id, $item_id, 1, $product['price']]);
        }
    }

    // Vider le panier après la commande
    unset($_SESSION['cart']);

    $stripe_url = 'https://buy.stripe.com/test_CN214C0B93gM6Vc3cc';
    // $stripe_url = "checkout.php";
    header("Location: $stripe_url?order_id=$order_id");
    exit;

    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
        exit;
    }
} else {
    echo "Le panier est vide.";
}
?>
<?php 
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("UPDATE orders SET status = 1 WHERE id = ?");
        $stmt->execute([$order_id]);

        if ($stmt->rowCount() > 0) {
            echo "Le status de la commadande a été mis à jour avec succès.";
        } else {
            echo "Aucune commande trouvée pour cet ID.";
        }
        header("Location: order_history.php");
        exit;

    } catch (PDOException $e) {
        echo "Erreur lors de la maj du statut : " . $e->getMessage();
        exit;
    }
} else {
    echo "ID de commande manquant.";
    exit;
}
?>
