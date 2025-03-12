<?php
session_start();
include 'php/bdd.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $priceQuery  = $conn->query("SELECT MIN(price) AS min_price, MAX(price) AS max_price FROM products");
    $priceResult = $priceQuery->fetch(PDO::FETCH_ASSOC);
    $minPrice = $priceResult['min_price'] ?? 0;
    $maxPrice = $priceResult['max_price'] ?? 50;

    $sql = "SELECT id, name, image, video, price, type FROM products WHERE 1=1";
    $filterConditions = [];

    if (isset($_GET['filter_php'])) {
        $filterConditions[] = "type = 'PHP'";
    }
    if (isset($_GET['filter_css'])) {
        $filterConditions[] = "type = 'CSS'";
    }
    if (isset($_GET['filter_js'])) {
        $filterConditions[] = "type = 'JS'";
    }
    if (isset($_GET['filter_mysql'])) {
        $filterConditions[] = "type = 'MySQL'";
    }

    if (!empty($filterConditions)) {
        $sql .= " AND (" . implode(" OR ", $filterConditions) . ")";
    }

    if (isset($_GET['price_min']) && isset($_GET['price_max'])) {
        $min_price = (float) $_GET['price_min'];
        $max_price = (float) $_GET['price_max'];        
        $sql .= " AND price BETWEEN :min_price AND :max_price";
    }

    $stmt = $conn->prepare($sql);
    if (isset($_GET['price_min']) && isset($_GET['price_max'])) {
        $stmt->bindParam(':min_price', $min_price, PDO::PARAM_INT);
        $stmt->bindParam(':max_price', $max_price, PDO::PARAM_INT);
    }
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
    exit();
    
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue de Produits</title>
    <link rel="stylesheet" href="css/style2.css">
</head>
<body>

<?php include 'php/navbar.php'; ?>

<h1>Propositions de cours en PHP</h1>

<form method="get" id="filterForm">
    <label>
        <input type="checkbox" name="filter_php" <?php echo isset($_GET['filter_php']) ? 'checked' : ''; ?>>
        En PHP
        <input type="checkbox" name="filter_css" <?php echo isset($_GET['filter_css']) ? 'checked' : ''; ?>>
        En CSS
        <input type="checkbox" name="filter_js" <?php echo isset($_GET['filter_js']) ? 'checked' : ''; ?>>
        En JS
        <input type="checkbox" name="filter_mysql" <?php echo isset($_GET['filter_mysql']) ? 'checked' : ''; ?>>
        En MySQL
    </label>
    <div class="price-slider">
        <input type="range" name="price_min" min="<?php echo $minPrice; ?>" max="<?php echo $maxPrice; ?>" value="<?php echo isset($_GET['price_min']) ? $_GET['price_min'] : $minPrice; ?>" step="1" id="minPrice">
        <input type="range" name="price_max" min="<?php echo $minPrice; ?>" max="<?php echo $maxPrice; ?>" value="<?php echo isset($_GET['price_max']) ? $_GET['price_max'] : $maxPrice; ?>" step="1" id="maxPrice">
    </div>
    <div class="price-values">
        <span>Prix min: <span id="price-min"><?php echo isset($_GET['price_min']) ? $_GET['price_min'] : $minPrice; ?></span> €</span>
        <span>Prix max: <span id="price-max"><?php echo isset($_GET['price_max']) ? $_GET['price_max'] : $maxPrice; ?></span> €</span>
    </div>
</form>
<div class="product-list">
    <?php foreach ($products as $product): ?>
        <div class="product-item">
            <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            
            <!--<video controls>
                <source src="<?php //echo htmlspecialchars($product['video']); ?>" type="video/mp4">
                <?php //$_COOKIE ?>Votre navigateur ne supporte pas les vidéos.
            </video>-->

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

    <?php include 'php/footer.php'; ?>

    <script src="js/index.js"></script>
</body>
</html>
