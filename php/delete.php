<?php 
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin.php");
    exit();
}
require_once 'classes/Database.php';

try {
    $conn = Database::getInstance()->getConnection();
    
    if(isset($_GET['table']) && isset($_GET['id'])) {
        $table = $_GET['table'];
        $id = $_GET['id'];

        $stmt = $conn->prepare("DELETE FROM $table WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: admin_dashboard.php?table=$table&success=1");
        exit();

    } else {
        echo "Table ou ID manquant.";
    }
} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

?>
