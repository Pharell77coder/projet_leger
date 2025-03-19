<?php
include 'bdd.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $video_title = $_POST['video_title'];
    $nom = $_POST['nom'];
    $commentaire = $_POST['commentaire'];
    $note = intval($_POST['note']);

    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO avis (video_title, nom, commentaire, note) VALUES (?, ?, ?, ?)");
    $stmt->execute([$video_title, $nom, $commentaire, $note]);

    header("Location: accueil.php");
    exit;
}
?>
