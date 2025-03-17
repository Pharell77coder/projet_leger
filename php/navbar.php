<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['HTTP_HOST'] === 'localhost') {
    $base_url = "/projet_leger/";
} else {
    $base_url = "/"; 
}

include'bdd.php';

$favoris_exist = false;

if (isset($_SESSION['username'])) {
    try {
        if (!isset($pdo)) {
            $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM favoris WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $_SESSION['username']]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $favoris_exist = true;
        }
    } catch (PDOException $e) {
        error_log("Erreur : ". $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/navbar.css">
</head>
<body>
    
    <nav class="navbar">
        <div class="navbar-left">
            <img src="<?php echo $base_url; ?>images/logo.png" alt="Logo" class="navbar-logo">
        </div>

        <div class="search-bar">
            <form action="<?php echo $base_url; ?>php/search.php" method="get">
                <input class="search-bar-input" type="text" name="query" placeholder="Rechercher une vidéo...." required>
                <button type="submit" class="search-button">
                    <img src="<?php echo $base_url; ?>images/loupe.png" alt="Rechercher" class="search-icon">
                </button>
            </form> 
        </div>

        <div class="navbar-right">
            <?php if (!isset($_SESSION['username'])): ?>
                <a href="<?php echo $base_url; ?>php/accueil.php">Home</a>
                <a href="<?php echo $base_url; ?>index.php">Produit</a>
                <a href="<?php echo $base_url; ?>php/connexion.php" class="cart-link">
                    <img src="<?php echo $base_url; ?>images/connexion.png" alt="Connexion" class="cart-icon" title="Connexion">
                </a>
                <a href="<?php echo $base_url; ?>php/inscription.php" class="cart-link">
                    <img src="<?php echo $base_url; ?>images/formulaire.png" alt="Formulaire" class="cart-icon" title="Formulaire">
                </a>
            <?php else: ?>
                <a href="<?php echo $base_url; ?>php/accueil.php">Home</a>
                <a href="<?php echo $base_url; ?>index.php">Produit</a>
                <a href="<?php echo $base_url; ?>php/favoris.php">Favoris</a>
                <a href="<?php echo $base_url; ?>php/compte.php" class="cart-link">
                    <img src="<?php echo $base_url; ?>images/utilisateur.png" alt="Utilisateur" class="cart-icon" title="Utilisateur">
                </a>
                <a href="<?php echo $base_url; ?>php/cart.php" class="cart-link">
                    <img src="<?php echo $base_url; ?>images/panier.png" alt="Panier" class="cart-icon" title="Panier">
                </a>
                <a href="<?php echo $base_url; ?>php/connexion.php" class="cart-link">
                    <img src="<?php echo $base_url; ?>images/deconnexion.png" alt="Déconnexion" class="cart-icon" title="Déconnexion">
                </a>
            <?php endif; ?>
        </div>
    </nav>
</body>
</html>
