<?php 
session_start();
require_once 'classes/Database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    try {
        $conn = Database::getInstance()->getConnection();

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $user['username'];
                header("Location: ../index.php");
                exit();
            } else {
                $error_message = "Mot de passe incorrect.";
            }
        } else {
            $error_message = "Email non trouvé.";
        }
    } catch (PDOException $e) {
        $error_message = "Erreur SQL : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de connexion</title>
    <link rel="stylesheet" href="../css/connexion.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<section class="form-section">
    <div class="signup-container">
        <h2>Connexion</h2>

        <?php if (!empty($error_message)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <form action="connexion.php" method="POST">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Connexion">
        </form>
        <p>Pas encore inscrit ? <a href="inscription.php" class="register-link">Inscrivez-vous ici</a></p>        
        <p>Mot de passe oublié ? <a href="reset_password_request.php" class="forgot-password-link">Cliquez ici</a></p>
    </div>
</section>
    <?php include 'footer.php'; ?>
</body>
</html>
