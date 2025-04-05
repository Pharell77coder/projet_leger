<?php
session_start();

require 'classes/FavorisManager.php';

if (!isset($_SESSION['username'])) {
    die("L'utilisateur n'est pas connecté.");
}

$favorisManager = new FavorisManager();
$favoris = $favorisManager->getUserFavoris($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Favoris</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/favoris.css">
</head>
<body>
<?php include 'navbar.php'; ?><br>

<h1>Mes Favoris</h1>
<div class="product-list">
    <?php if (empty($favoris)): ?>
        <p><br>Aucun favori trouvé.</p>
    <?php else: ?>
        <?php foreach ($favoris as $favori): ?>
            <div class="product-item">
                <img src="../<?php echo htmlspecialchars($favori['image']); ?>" alt="<?php echo htmlspecialchars($favori['name']); ?>">
                <h2>
                    <a href="video.php?id=<?php echo htmlspecialchars($favori['id']); ?>">
                        <?php echo htmlspecialchars($favori['name']); ?>
                    </a>
                </h2>
                <p>Prix: <?php echo htmlspecialchars($favori['price']); ?> €</p>
                <div class="favorite-icon" data-video-id="<?php echo htmlspecialchars($favori['id']); ?>">Favori</div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script src="../js/favoris.js"></script>
<?php include 'footer.php'; ?>
</body>
</html>
