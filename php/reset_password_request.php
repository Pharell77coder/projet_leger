<?php 
require_once 'classes/Database.php';

date_default_timezone_set('Europe/Paris');

// Vérifier si la requête est une requête POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et nettoyer l'email envoyé via le formulaire
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    
    if (!$email) {
        echo "<p style='color: red;'>Email invalide.</p>";
        exit;
    }

    try {
        $conn = Database::getInstance()->getConnection();

        // Supprimer les anciens tokens expirés
        $stmt = $conn->prepare("DELETE FROM password_resets WHERE expires_at < NOW()");
        $stmt->execute();

        // Vérifier si l'email existe dans la table 'users'
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Générer un token unique sécurisé
            $token = bin2hex(random_bytes(32));

            // Définir une expiration de 15 minutes
            $expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));

            // Démarrer une transaction
            $conn->beginTransaction();

            // Insérer le token dans la table 'password_resets'
            $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires_at, created_at) VALUES (:email, :token, :expires_at, NOW())");
            $stmt->execute([
                'email' => $email,
                'token' => $token,
                'expires_at' => $expiry
            ]);

            // Valider la transaction
            $conn->commit();

            // Créer un lien de réinitialisation basé sur le domaine actuel
            $resetLink = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . "/reset_password.php?token=" . $token;

            // Préparer le sujet de l'email
            $subject = "=?UTF-8?B?" . base64_encode("Réinitialisation de votre mot de passe") . "?=";

            // Contenu du mail
            $message = "
            <html>
            <head>
                <title>Réinitialisation de votre mot de passe</title>
            </head>
            <body>
                <p>Bonjour,</p>
                <p>Cliquez sur le lien ci-dessous pour réinitialiser votre mot de passe :</p>
                <p><a href='".$resetLink."' style='color: blue; text-decoration: underline;'>Réinitialiser mon mot de passe</a></p>
                <p>Ce lien expirera dans 15 minutes.</p>
                <p>Si vous n'avez pas demandé cette réinitialisation, ignorez cet e-mail.</p>
            </body>
            </html>";

            // Configurer les en-têtes de l'e-mail
            $headers  = "From: no-reply@" . $_SERVER['HTTP_HOST'] . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $headers .= "Content-Transfer-Encoding: 8bit\r\n";

            // Envoyer l'e-mail
            if (mail($email, $subject, $message, $headers)) {
                echo "<p style='color: green;'>Lien de réinitialisation a été envoyé à votre adresse e-mail.</p>";
            } else {
                echo "<p style='color: red;'>Erreur lors de l'envoi de l'email.</p>";
            }
        } else {
            echo "<p style='color: red;'>Aucun compte trouvé pour cet e-mail.</p>";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/connexion.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<section class="form-section">
<div class="login-container">
    <div class="form-container">
        <h2>Réinitialisation du mot de passe</h2>
        <form action="reset_password_request.php" method="POST">
            <label for="email">Entrez votre adresse e-mail :</label>
            <input type="email" id="email" name="email" required>
            <button type="submit">Envoyer</button>
        </form>
    </div>
</div>
</section>
<?php include 'footer.php'; ?>
</body>
</html>