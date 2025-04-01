<?php
session_start();
require_once 'classes/Database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $video_title = htmlspecialchars(trim($_POST['video_title']));
    $nom = htmlspecialchars(trim($_POST['nom']));
    $commentaire = htmlspecialchars(trim($_POST['commentaire']));
    $note = intval($_POST['note']);

    try {
        // Récupérer la connexion à la base de données depuis la classe Database
        $pdo = Database::getInstance()->getConnection();

        // Préparer la requête
        $stmt = $pdo->prepare("INSERT INTO avis (video_title, nom, commentaire, note) VALUES (:video_title, :nom, :commentaire, :note)");

        // Exécuter la requête en liant les paramètres
        $stmt->execute([
            ':video_title' => $video_title,
            ':nom' => $nom,
            ':commentaire' => $commentaire,
            ':note' => $note
        ]);

        // Redirection après insertion réussie
        header("Location: accueil.php");
        exit;
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>

