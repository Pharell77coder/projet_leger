<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <!--<link rel="stylesheet" href="navbar.css">-->
</head>
<body>
<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
    
    <nav class="navbar">
        <div class="navbar-left">
            <img src="images/logo.png" alt="Logo" class="navbar-logo">
        </div>

        <div class="search-bar">
            <form action="search.php" method="get" style="display: flex; align-items: center;">
                <input type="text" name="query" placeholder="Rechercher une vidéo...." required>
                <button type="submit" class="search-button">
                    <img src="images/loupe.png" alt="Rechercher" class="search-icon">
                </button>
            </form> 
        </div>

        <div class="navbar-right">
            <?php if (!isset($_SESSION['username'])): ?>
                <a href="index.php">Produit</a>
                <a href="connexion.php" class="cart-link">
                    <img src="images/connexion.png" alt="Connexion" class="cart-icon" title="Connexion">
                </a>
                <a href="inscription.php" class="cart-link">
                    <img src="images/formulaire.png" alt="Formulaire" class="cart-icon" title="Formulaire">
                </a>
            <?php else: ?>
                <a href="index.php">Produit</a>
                <a href="compte.php" class="cart-link">
                    <img src="images/utilisateur.png" alt="Utilisateur" class="cart-icon" title="Utilisateur">
                </a>
                <a href="cart.php" class="cart-link">
                    <img src="images/panier.png" alt="Panier" class="cart-icon" title="Panier">
                </a>
                <a href="connexion.php" class="cart-link">
                    <img src="images/deconnexion.png" alt="Déconnexion" class="cart-icon" title="Déconnexion">
                </a>
            <?php endif; ?>
        </div>
    </nav>
</body>
</html>
