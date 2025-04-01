<?php
session_start();
require_once 'Database.php';

class UserSession {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function isUserLoggedIn() {
        return isset($_SESSION['username']);
    }

    public function logout() {
        session_destroy();
        header("Location: connexion.php");
        exit();
    }

    public function getVideos() {
        $stmt = $this->pdo->query("SELECT * FROM products");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getComments($videoTitle) {
        $stmt = $this->pdo->prepare("SELECT * FROM avis WHERE video_title = ? ORDER BY date_ajout DESC");
        $stmt->execute([$videoTitle]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
