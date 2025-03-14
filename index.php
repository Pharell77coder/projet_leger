<?php
session_start();
include 'php/bdd.php';

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        global $servername, $dbname, $dbusername, $dbpassword;
        $this->conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}

class Product {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getPriceRange() {
        $query = $this->conn->query("SELECT MIN(price) AS min_price, MAX(price) AS max_price FROM products");
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return [
            'minPrice' => $result['min_price'] ?? 0,
            'maxPrice' => $result['max_price'] ?? 5000
        ];
    }

    public function getFilteredProducts($filters) {
        $sql = "SELECT id, name, image, video, price, type FROM products WHERE 1=1";
        $params = [];
        
        if (!empty($filters['types'])) {
            $sql .= " AND type IN (" . implode(", ", array_fill(0, count($filters['types']), '?')) . ")";
            $params = array_merge($params, $filters['types']);
        }
        
        if (!empty($filters['price_min']) && !empty($filters['price_max'])) {
            $sql .= " AND price BETWEEN ? AND ?";
            $params[] = (float) $filters['price_min'];
            $params[] = (float) $filters['price_max'];
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

class Cart {
    public static function addToCart($product_id) {
        $_SESSION['cart'][] = $product_id;
        header("Location: cart.php");
        exit();
    }
}

$productHandler = new Product();
$priceRange = $productHandler->getPriceRange();

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

$products = $productHandler->getFilteredProducts($filters);

if (!empty($_POST['add_to_cart']) && !empty($_POST['product_id'])) {
    Cart::addToCart($_POST['product_id']);
}
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

<h1>Propositions de cours en PHP</h1>

<form method="get" id="filterForm" class="filterForm" >
    <label>
        <input type="checkbox" name="filter_Électronique" <?php echo !empty($_GET['filter_Électronique']) ? 'checked' : ''; ?>> Électronique
        <input type="checkbox" name="filter_Mode" <?php echo !empty($_GET['filter_Mode']) ? 'checked' : ''; ?>> Mode & Accessoires
        <input type="checkbox" name="filter_Maison" <?php echo !empty($_GET['filter_Maison']) ? 'checked' : ''; ?>> Maison & Cuisine
        <input type="checkbox" name="filter_Beauté" <?php echo !empty($_GET['filter_Beauté']) ? 'checked' : ''; ?>> Beauté & Santé
        <input type="checkbox" name="filter_Sport" <?php echo !empty($_GET['filter_Sport']) ? 'checked' : ''; ?>> Sport & Loisirs
    </label>
    <div class="price-slider">
        <input type="range" id="minPrice" name="price_min" min="<?php echo $priceRange['minPrice']; ?>" max="<?php echo $priceRange['maxPrice']; ?>" value="<?php echo $_GET['price_min'] ?? $priceRange['minPrice']; ?>" step="1">
        <input type="range" id="maxPrice" name="price_max" min="<?php echo $priceRange['minPrice']; ?>" max="<?php echo $priceRange['maxPrice']; ?>" value="<?php echo $_GET['price_max'] ?? $priceRange['maxPrice']; ?>" step="1">
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