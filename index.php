<?php
session_start();
require 'php/classes/Database.php';
require 'php/classes/Product.php';

$productClass = new Product();
$priceRange = $productClass->getPriceRange() ?? ['minPrice' => 0, 'maxPrice' => 5000];

$filters = [
    'types' => array_filter([
        !empty($_GET['filter_Électronique']) ? 'Électronique' : null,
        !empty($_GET['filter_Mode']) ? 'Mode & Accessoires' : null,
        !empty($_GET['filter_Maison']) ? 'Maison & Cuisine' : null,
        !empty($_GET['filter_Beauté']) ? 'Beauté & Santé' : null,
        !empty($_GET['filter_Sport']) ? 'Sport & Loisirs' : null
    ]),
    'price_min' => isset($_GET['price_min']) ? (float) $_GET['price_min'] : $priceRange['minPrice'],
    'price_max' => isset($_GET['price_max']) ? (float) $_GET['price_max'] : $priceRange['maxPrice']
];

$products = $productClass->getFilteredProducts($filters);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue de Produits</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/product.css">
</head>
<body>
<?php include 'php/navbar.php'; ?>

<h1>Catalogue de Produits</h1>

<form method="get" id="filterForm" class="filterForm">
    <label>
        <input type="checkbox" name="filter_Électronique" onchange="this.form.submit()" <?php echo !empty($_GET['filter_Électronique']) ? 'checked' : ''; ?>> Électronique
        <input type="checkbox" name="filter_Mode" onchange="this.form.submit()" <?php echo !empty($_GET['filter_Mode']) ? 'checked' : ''; ?>> Mode & Accessoires
        <input type="checkbox" name="filter_Maison" onchange="this.form.submit()" <?php echo !empty($_GET['filter_Maison']) ? 'checked' : ''; ?>> Maison & Cuisine
        <input type="checkbox" name="filter_Beauté" onchange="this.form.submit()" <?php echo !empty($_GET['filter_Beauté']) ? 'checked' : ''; ?>> Beauté & Santé
        <input type="checkbox" name="filter_Sport" onchange="this.form.submit()" <?php echo !empty($_GET['filter_Sport']) ? 'checked' : ''; ?>> Sport & Loisirs
    </label>

    <div class="price-slider">
        <input type="range" id="minPrice" name="price_min" min="<?php echo $priceRange['minPrice']; ?>" max="<?php echo $priceRange['maxPrice']; ?>" value="<?php echo $_GET['price_min'] ?? $priceRange['minPrice']; ?>" step="1" onchange="this.form.submit()">
        <input type="range" id="maxPrice" name="price_max" min="<?php echo $priceRange['minPrice']; ?>" max="<?php echo $priceRange['maxPrice']; ?>" value="<?php echo $_GET['price_max'] ?? $priceRange['maxPrice']; ?>" step="1" onchange="this.form.submit()">
    </div>

    <div class="price-values">
        <span>Prix min: <span id="price-min"><?php echo $_GET['price_min'] ?? $priceRange['minPrice']; ?></span> €</span>
        <span>Prix max: <span id="price-max"><?php echo $_GET['price_max'] ?? $priceRange['maxPrice']; ?></span> €</span>
    </div>
</form>

<div class="product-list">
    <?php foreach ($products as $product): ?>
        <div class="product-item">
            <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            <h2><a href="php/video.php?id=<?php echo htmlspecialchars($product['id']); ?>"> <?php echo htmlspecialchars($product['name']); ?> </a></h2>
            <p>Prix: <?php echo htmlspecialchars($product['price']); ?>€</p>
            <form method="post" action="php/add_to_cart.php">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                <button type="submit" name="add_to_cart">Ajouter au Panier</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

<a href="php/cart.php">Voir le Panier</a>
<?php include 'php/footer.php'; ?>
<script src="js/index.js"></script>
</body>
</html>
