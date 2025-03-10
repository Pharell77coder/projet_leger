<?php
session_start();
include 'bdd.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $priceQueryh  = $pdo->query("SELECT MIN(price) AS min_price, MAX(price) AS maw_price from from products");
    $priceResult = $priceQuery->fetch(PDO::FETCH_ASSOC);
    $minPrice = $priceResult['min_price'] ?? 0;
    $maxPrice = $priceResult['max_price'] ?? 35.00;

    $filters = [];
    $sql = "SELECT id, name, image, video, price, type FROM products WHERE 1=1";

    $filterConditions = [];
    if (isset($_GET['filter_php'])) {
        $filterConditions[] = "type = 'PHP'";
    }
    
    $filterConditions = [];
    if (isset($_GET['filter_css'])) {
        $filterConditions[] = "type = 'CSS'";
    }
    
    $filterConditions = [];
    if (isset($GET['filter'])) {
        $filterConditions[] = "type = 'PHP'";
    }
    
    $filterConditions = [];
    if (isset($_GET['filter_php'])) {
        $filterConditions[] = "type = 'PHP'";
    }

    $stmt = $conn->prepare("SELECT id, name, image, video, price FROM products");
    $stmt->execute(); 
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
        echo "Erreur : " . $e->getMessage();
        exit();
}

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $_SESSION['cart'][] = $product_id;
    header("Location: cart.php");
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue de Produits</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<h1>Propositions de cours en PHP</h1>

<div class="product-list">
    <?php foreach ($products as $product): ?>
        <div class="product-item">
            <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            
            <video controls>
                <source src="<?php echo htmlspecialchars($product['video']); ?>" type="video/mp4">
                <?php $_COOKIE ?>Votre navigateur ne supporte pas les vidéos.
            </video>

            <h2>
                <a href="video.php?id=<?php echo htmlspecialchars($product['id']); ?>">
                    <?php echo htmlspecialchars($product['name']); ?>
                </a>
            </h2>
            <p>Prix: <?php echo htmlspecialchars($product['price']); ?>€</p>

            <form method="post">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                <button type="submit" name="add_to_cart">Ajouter au Panier</button>
            </form>
        </div>
    <?php endforeach; ?>
    </div>
    <a href="cart.php">Voir le Panier</a>
    <?php include 'footer.php'; ?>
</body>
</html>
