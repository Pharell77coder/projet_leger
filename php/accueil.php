<?php

session_start();

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Europe/Paris');

include 'bdd.php';

if (!isset($_SESSION['username'])) {
    header("Location: connexion.php");
    exit;
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: connexion.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>
</head>
<body>
    <?php include 'navbar.php'; 
    $videos =[
        ['title' => 'Titre', 'description' => 'une description', 'video' => '../videos/video.mp4'],
        ['title' => 'Titre', 'description' => 'une description', 'video' => '../videos/video.mp4'],
        ['title' => 'Titre', 'description' => 'une description', 'video' => '../videos/video.mp4'],
        ['title' => 'Titre', 'description' => 'une description', 'video' => '../videos/video.mp4'],
    ];

    foreach ($videos as $video) {
        echo '<div class="video-card>';
        echo '<h3>' . htmlspecialchars($video['title']) . '</h3>';
        echo '<video controls class="video-player">';
        echo '<source src="' . htmlspecialchars($video['video']) . '" type="video/mp4">';
        echo 'Votre navigatuer ne supporte pas la vidéo.';
        echo '</video>';
        echo '<p>' . htmlspecialchars($video['description']) . '</p>';
        echo '<a href="' . urldecode($video['title']) . '">Voir la vidéo</a>';
        echo '</div>';

        if (isset($_SESSION['username'])) {
            echo '<form action="ajouter_avis.php" method="post" class="avis_form">';
            echo '<input type="hidden" name="video_title" value="' . htmlspecialchars($video['title']) . '">';
            echo '<input type="hidden" name="nom" value="' . htmlspecialchars($_SESSION['username']) . '">';
        
            echo '<label>Commentaire :</label>';
            echo '<textarea name="commentaire" required></textarea>';
        
            echo '<input type="hidden" name="note" id="note_' . htmlspecialchars($video['title']) . '" value="0">';
        
            echo '<div class="star-rating" data-video="' . htmlspecialchars($video['title']) . '">';
            for ($i = 1; $i <= 5; $i++) {
                echo '<span class="star" data-value="' . $i . '">★</span>';
            }
            echo '</div>';
        
            echo '<button type="submit">Envoyer l\'avis</button>';
            echo '</form>';
        } else {
            echo '<p><a href="login.php">Connectez-vous</a> pour laisser un avis.</p>';
        }
        $stmt = $pdo->prepare("SELECT * FROM avis WHERE video_title = ? ORDER BY date_ajout DESC");
        $stmt->execute([$video['title']]);
        $avis = $stmt->fetchAll();

        echo '<div class="avis-section">';
        foreach ($avis as $a) {
            echo '<div class="avis">';
            echo '<strong>' . htmlspecialchars($a['nom']) . '</strong> ';
            echo '<span>' . str_repeat('★', $a['note']) . '</span>';
            echo '<p>' . nl2br(htmlspecialchars($a['commentaire'])) . '</p>';
            echo '<small>' . $a['date_ajout'] . '</small>';
            echo '</div>';
        }
        echo '</div>';

    }

    include 'footer.php'; ?>
    <script src="../js/accueil.js"></script>
</body>
</html>