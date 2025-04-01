<?php
/* pas de classe */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'classes/Database.php';

    // Récupérer et nettoyer les données du formulaire
    $token = htmlspecialchars(trim($_POST['token'])); // Le token unique pour réinitialisation
    $new_password = trim($_POST['new_password']); // Nouveau mot de passe
    $confirm_password = trim($_POST['confirm_password']); // Confirmation du mot de passe

    // Validation des mots de passe
    if ($new_password !== $confirm_password) {
        echo "<p style='color: red;'>Le mot de passe ne correspond pas.</p>";
        exit; // Arrêter l'exécution si les mots de passe ne correspondent pas
    }

    if (strlen($new_password) < 8) {
        echo "<p style='color: red;'>Le mot de passe doit contenir au moins 8 caractères.</p>";
        exit; // Arrêter l'exécution si les mots de passe ne correspondent pas
    }
    try {
        $pdo = Database::getInstance()->getConnection();

        // Vérifier que le token est valide et non expiré
        $stmt = $pdo->prepare("SELECT email FROM password_resets WHERE token = :token AND expires_at > NOW()");
        $stmt->bindParam(':token', $token); // Lier le paramètre :token
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Si le token est valide, récupérer l'adresse e-mail associée
            $email = $stmt->fetchColumn();

            // Hacher le nouveau mdp pour le stockage
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Maj le mdp de l'utilisateur dans la bdd
            $pdo->prepare("UPDATE users SET password = :password WHERE email = :email")
            ->execute(['password' => $hashed_password, 'email' => $email]);
        
        // Supprimer le token utilisé pour éviter sa réutilisation
        $pdo->prepare("DELETE FROM password_resets WHERE token = :token")
            ->execute(['token' => $token]);
        
        // Rediriger l'utilisateur vers la page de connexion après succès
        header("Location: connexion.php");
        exit;
        } else {
            // Message d'erreur si le token est invalide ou expiré
            echo "<p style='color: red;'>Le lien de réinitialisation est invalide ou expiré.</p>";
        }
        
        } catch (PDOException $e) {
            // Afficher une erreur en cas de problème avec la bdd
            echo "Erreur : " . $e->getMessage();
        }
        
} else if (isset($_GET['token'])) {
    $token = htmlspecialchars($_GET['token']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe oublié</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/connexion.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="login-container">
    <div class="form-container">
        <h2>Réinitialisation du mot de passe</h2>
        <form action="reset_password.php" method="POST">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token); ?>">
            <label for="new_password">Nouveau mot de passe :</label>
            <input type="password" id="new_password" name="new_password" required>
            <label for="confirm_password">Confirmer le mot de passe :</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <button type="submit">Réinitialiser</button>
        </form>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>