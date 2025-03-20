<?php
session_start();

if (!isset($_SESSION['username'])) {
    die("L'utilisateur n'est pas connecté.");
}

$user_id = $_SESSION['username'];

include 'bdd.php';

try {
    if (!isset($pdo)) {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    $stmt = $pdo->prepare("
        SELECT p.id, p.name, p.image, p.video, p.price, p.type
        FROM favoris f
        JOIN products p ON f.video_id = p.id
        WHERE f.user_id = :user_id
    ");
    $stmt->execute(['user_id' => $user_id]);
    $favoris = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/favorites.css">
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
                <img src="../<?php echo htmlspecialchars($favori['image']); ?>" 
                     alt="<?php echo htmlspecialchars($favori['name']); ?>">
                <video controls>
                    <source src="<?php echo htmlspecialchars($favori['video']); ?>" 
                            type="video/mp4">
                    Votre navigateur ne supporte pas les vidéos.
                </video>
                <h2>
                    <a href="video.php?id=<?php echo htmlspecialchars($favori['id']); ?>">
                        <?php echo htmlspecialchars($favori['name']); ?>
                    </a>
                </h2>
                <p>Prix: <?php echo htmlspecialchars($favori['price']); ?> €</p>
                <div class="favorite-icon" data-video-id="<?php echo htmlspecialchars($favori['id']); ?>">

                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<script src="../js/favoris.js"></script>
<?php include 'footer.php'; ?>
</body>
</html>