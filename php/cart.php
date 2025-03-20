<?php
session_start();

include 'bdd.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT id, name, image, video, price FROM products");
    $stmt->execute();
    $products = [];

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $product) {
        $products[$product['id']] = [
            'name' => $product['name'],
            'image' => $product['image'],
            'video' => $product['video'],
            'price' => (float)$product['price'] // Conversion en float
        ];
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    exit();
}

// Initialiser le panier si non défini
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$total = 0;

// Vider le panier
if (isset($_POST['clear_cart'])) {
    $_SESSION['cart'] = [];
    header("Location: cart.php");
    exit();
}

// Ajouter un produit au panier
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];

    if (isset($products[$product_id])) { // Vérifie si le produit existe
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]++; // Incrémente la quantité
        } else {
            $_SESSION['cart'][$product_id] = 1; // Ajoute le produit avec quantité 1
        }
    }

    header("Location: cart.php");
    exit();
}

// Retirer un produit du panier (diminue la quantité)
if (isset($_POST['sub_to_cart'])) {
    $product_id = $_POST['product_id'];

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]--;

        // Supprime l'article s'il tombe à 0
        if ($_SESSION['cart'][$product_id] <= 0) {
            unset($_SESSION['cart'][$product_id]);
        }
    }

    header("Location: cart.php");
    exit();
}

// Supprimer complètement un produit du panier
if (isset($_POST['remove_item'])) {
    $item_id = $_POST['item_id'];

    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
    }

    header("Location: cart.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/cart.css">
</head>
<body>

    <h1>Votre Panier</h1>

    <?php if (!empty($_SESSION['cart'])): ?>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Nom du Produit</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $item_id => $quantity): ?>
                    <?php 
                    if (isset($products[$item_id])) {
                        $product = $products[$item_id];
                        $subtotal = $product['price'] * $quantity;
                        $total += $subtotal;
                    ?>
                    <tr>
                        <td><img src="../<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" width="100"></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo number_format($product['price'], 2, ',', ' '); ?> €</td>
                        <td><?php echo $quantity; ?></td>
                        <td><?php echo number_format($subtotal, 2, ',', ' '); ?> €</td>
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?php echo $item_id; ?>">
                                <button type="submit" name="add_to_cart">Ajouter</button>
                            </form>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?php echo $item_id; ?>">
                                <button type="submit" name="sub_to_cart">Retirer</button>
                            </form>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
                                <button type="submit" name="remove_item">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p>Total : <?php echo number_format($total, 2, ',', ' '); ?> €</p>

        <form method="post">
            <button type="submit" name="clear_cart">Vider le Panier</button>
        </form>

        <form method="get" action="verification_payement.php">
            <button type="submit">Finaliser le Panier</button>
        </form>

    <?php else: ?>
        <p>Votre panier est vide.</p>
    <?php endif; ?>

    <a class="btn" href="../index.php">Retourner au Catalogue</a>

</body>
</html>
