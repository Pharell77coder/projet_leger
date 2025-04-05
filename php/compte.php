<?php
session_start();
require_once 'classes/Database.php';
require 'classes/UserProfileManager.php';

if (!isset($_SESSION['username'])) {
    header("Location: connexion.php");
    exit();
}

// Initialisation de la gestion du profil utilisateur
$profileManager = new UserProfileManager($_SESSION['username']);
$user = $profileManager->getUser();
$message = "";

// Vérification de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newUsername = htmlspecialchars(trim($_POST['username']));
    $newEmail = htmlspecialchars(trim($_POST['email']));
    $newPassword = !empty($_POST['password']) ? $_POST['password'] : null;
    
    $message = $profileManager->updateUserProfile($newUsername, $newEmail, $newPassword);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page compte</title>
    <link rel="stylesheet" href="../css/connexion.css">
    <link rel="stylesheet" href="../css/global.css">
</head>
<body>
    <?php include 'navbar.php'; ?><br>

    <div class="profile-container">
        <h2>Mon profil</h2>
        <?php if ($message): ?>
            <p style='color: green;'><?= htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <form action="compte.php" method="POST">
            <label for="username">Nom d'utilisateur : </label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']); ?>" required>

            <label for="email">Email : </label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>

            <label for="password">Nouveau mot de passe (laisser vide pour ne pas modifier) : </label>
            <input type="password" id="password" name="password">

            <input type="submit" value="Mettre à jour">
        </form>
    </div>
    <a href="order_history.php">Historique des payement</a>

    <?php include 'footer.php'; ?><br>
</body>
</html>
