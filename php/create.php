<?php 
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin.php");
    exit();
}

include 'bdd.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['table'])) {
        $table = $_POST['table'];
        unset($_POST['table']); 

        if (!empty($_POST)) {
            $columns = array_keys($_POST);
            $placeholders = array_map(fn($col) => ":$col", $columns);

            $sql = "INSERT INTO $table (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $placeholders) . ")";
            $stmt = $conn->prepare($sql);

            foreach ($_POST as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }

            $stmt->execute();

            header("Location: admin_dashboard.php?table=$table&success=1");
            exit();
        } else {
            echo "Aucune donnée fournie.";
        }
    } else {
        echo "Table non spécifiée.";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
