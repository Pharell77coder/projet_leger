<?php 
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    include 'bdd.php';

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if(password_verify($password, $user['password'])){
                $_SESSION['username'] = $user['username'];
                header("Location: index.php");
                exit();
            } else {
                echo "<p style='color: red;'>Mot de passe incorrect.</p>";
            }
        } else {
            echo "<p style='color: red;'>Email non trouvé</p>";
        } 
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Erreur SQL : " . $e->getMessage() . "</p>";
    }
    $conn = null;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php //include 'navbar.php'; ?>
    <div class="signup-container">
        <h2>connexion</h2>
        <form action="connexion.php" method="POST">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="connexion">
        </form>
        
        <p>Pas encore inscrit ? <a href="inscription.php" class="register-link">Inscrivez-vous ici</a></p>
    </div>
    <?php //include 'footer.php'; ?>
</body>
</html>