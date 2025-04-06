<?php

require_once 'classes/UserSession.php';

$userSession = new UserSession();

if (!$userSession->isUserLoggedIn()) {
    header("Location: connexion.php");
    exit;
}

if (isset($_POST['logout'])) {
    $userSession->logout();
}

$videos = $userSession->getVideos();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>
    <link rel="stylesheet" href="../css/accueil.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
        
    <?php foreach ($videos as $video): ?>
        <div class="video-card">
            <h3><?= htmlspecialchars($video['name']) ?></h3>
            <video controls class="video-player">
                <source src="<?= htmlspecialchars($video['video']) ?>" type="video/mp4">
                Votre navigateur ne supporte pas les vidéos.
            </video>
            <p><?= htmlspecialchars($video['description']) ?></p>
            <a href="<?= urldecode($video['name']) ?>">Voir la vidéo</a>
            
            <?php if ($userSession->isUserLoggedIn()): ?>
                <form action="ajouter_avis.php" method="post" class="avis_form">
                    <input type="hidden" name="video_name" value="<?= htmlspecialchars($video['name']) ?>">
                    <input type="hidden" name="nom" value="<?= htmlspecialchars($_SESSION['username']) ?>">
                    
                    <label>Commentaire :</label>
                    <textarea name="commentaire" required></textarea>
                    
                    <input type="hidden" name="note" id="note_<?= htmlspecialchars($video['name']) ?>" value="0">
                    
                    <div class="star-rating" data-video="<?= htmlspecialchars($video['name']) ?>">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="star" data-value="<?= $i ?>">★</span>
                        <?php endfor; ?>
                    </div>
                    
                    <button type="submit">Envoyer l'avis</button>
                </form>
            <?php else: ?>
                <p><a href="login.php">Connectez-vous</a> pour laisser un avis.</p>
            <?php endif; ?>
            
            <?php $comments = $userSession->getComments($video['name']); ?>
            <div class="avis-section">
                <?php foreach ($comments as $comment): ?>
                    <div class="avis">
                        <strong><?= htmlspecialchars($comment['nom']) ?></strong>
                        <span><?= str_repeat('★', $comment['note']) ?></span>
                        <p><?= nl2br(htmlspecialchars($comment['commentaire'])) ?></p>
                        <small><?= $comment['date_ajout'] ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
    <div class="wrapper"></div>
<?php include 'footer.php'; ?>
    <script src="../js/accueil.js"></script>
</body>
</html>