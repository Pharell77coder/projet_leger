<?php
session_start();
require 'classes/Database.php';
require 'classes/Admin.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    $admin = new Admin();
    $error = $admin->login($email, $password);
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin</title>
    <link rel="stylesheet" href="../css/connexion.css">
</head>
<body>
<?php include 'navbar.php'; ?>

    <div class="login-container">
        <div class="form-container">
            <h2>Connexion Admin</h2>
            <form action="admin.php" method="POST">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
                <input type="submit" value="Se connecter">
            </form>
            <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>