<?php 
include 'bdd.php';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['id'])) {
        $video_id = (int) $_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $video_id]);
        $video = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$video) { 
            die("Vidéo introuvable");
        }
        $isFavorite = false;
        if (isset($_SESSION['username'])) {
            $user = $_SESSION['username'];
            $favStmt = $pdo->prepare(
                "SELECT COUNT(*) FROM favoris WHERE user_id = :user_id AND video_id = :video_id"
            );
            $favStmt->execute(['user_id' => $user, 'video_id' => $video_id]);
            $isFavorite = $favStmt->fetchColumn() > 0;
        }
    } else {
        die("ID de vidéo non spécifié.");
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    exit();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php htmlspecialchars($video['name'])?></title>
    <link rel="stylesheet" href="../css/video.css">
    <link rel="stylesheet" href="../css/global.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <div class="video-details">
            <div class="h1-container">
                <h1><?= htmlspecialchars($video['name']); ?></h1>
                <?php if (isset($_SESSION['username'])): ?>
                    <div class="favorite-icon" <?= $isFavorite ? 'added' : '' ?>" id="favorite-icon" data-video-id="<?= $video_id ?>">Coeur</div>
                <?php endif; ?>
            </div> 

            <div>
                <video controls>
                    <source src="<?= htmlspecialchars($video['video']); ?>" type="video/mp4">
                    Votre navigateur ne supporte pas les vidéos.
                </video>
            </div>

            <p><strong>Description :</strong> <?= htmlspecialchars($video['description']); ?></p>

            <div class="details-container">
                <div class="details-left">
                    <p>
                        <strong>Date de mise en ligne :</strong> <?= htmlspecialchars($video['upload_date']); ?><br>
                        <strong>Durée :</strong> <?= htmlspecialchars($video['duration']); ?>
                    </p>
                </div>
            </div>

            <div class="details-right">
                <form method="post" action="add_to_cart.php">
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($video['id']); ?>">
                    <button type="submit" name="add_to_cart">Ajouter au Panier</button>
                </form>
            </div>
        </div> 
    </div> 

    <script src="../js/video.js"></script>
    <?php include 'footer.php'; ?>
</body>
</html>