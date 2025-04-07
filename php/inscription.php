<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'inscription</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/connexion.css">
</head>
<body>
    <?php require 'navbar.php'; ?>
    <section class="form-section">
    <div class="signup-container">
        <h2>Inscription</h2>
        <form action="" method="POST" onsubmit="return validateForm()">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required> 

            <label for="confirm-password">Confirmer le mot de passe :</label>
            <input type="password" id="confirm-password" name="confirm-password" required> 

            <input type="submit" value="Inscription">
        </form>
        <p id="error-msg"></p>
    </div>
    </section>
    <script src="js/inscription.js"></script>

    <?php 
    require_once('classes/Database.php'); // Inclusion du fichier de connexion

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = htmlspecialchars(trim($_POST['username']));
        $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
        $password = trim($_POST['password']);
        $confirmPassword = trim($_POST['confirm-password']);

        if (!$email) {
            echo "<p style='color: red;'>Adresse email invalide.</p>";
        } elseif ($password !== $confirmPassword) {
            echo "<p style='color: red;'>Les mots de passe ne correspondent pas.</p>";
        } elseif (strlen($password) < 8) {
            echo "<p style='color: red;'>Le mot de passe doit contenir au moins 8 caractères.</p>";
        } else {
            try {
                // Récupérer la connexion
                $conn = Database::getInstance()->getConnection();

                // Vérifier si l'email existe déjà
                $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
                $stmt->bindParam(':email', $email);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    echo "<p style='color: red;'>Cet email est déjà utilisé.</p>";
                } else {
                    // Hachage du mot de passe
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    // Insertion des données
                    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $hashedPassword);

                    if ($stmt->execute()) {
                        header("Location: connexion.php");
                        exit();
                    } else {
                        echo "<p style='color: red;'>Une erreur est survenue.</p>";
                    }
                }
            } catch (PDOException $e) {
                echo "<p style='color: red;'>Erreur SQL : " . $e->getMessage() . "</p>";
            }
        }
    }
    ?>

    <?php require 'footer.php'; ?>
</body>
</html>
