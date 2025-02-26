<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Navbar</title>
        <link rel="stylesheet" href="navbar.css">
    </head>
    <body>
        <nav class="navbar">
            <div class="navbar-left">
                <img src="images/logo.jpg" alt="Logo" class="navbar-logo">
            </div>

            <div class="navbar-right">
                <?php if (!isset($_SESSION['loggedin'])): ?>
                <a href="index.php">index</a>
                <a href="accueil.php">acceuil</a>
                <a href="compte.php">compte</a>
                <a href="cart.php">panier</a>
                <a href="admin.php">admin</a>
                <?php else: ?>
                    <a href="logout.php">Déconnexion</a>
                <?php endif; ?>
            </div>
        </nav>
    </body>
</html>
