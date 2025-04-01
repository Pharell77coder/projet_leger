<?php
require_once 'Database.php';

class FavorisManager {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function toggleFavoris($user_id, $video_id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM favoris WHERE user_id = :user_id AND video_id = :video_id");
        $stmt->execute(['user_id' => $user_id, 'video_id' => $video_id]);
        $is_favorite = $stmt->fetchColumn() > 0;

        if ($is_favorite) {
            $stmt = $this->pdo->prepare("DELETE FROM favoris WHERE user_id = :user_id AND video_id = :video_id");
            $stmt->execute(['user_id' => $user_id, 'video_id' => $video_id]);
            return ['success' => true, 'action' => 'removed', 'message' => 'Vidéo retirée des favoris.'];
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO favoris (user_id, video_id) VALUES (:user_id, :video_id)");
            $stmt->execute(['user_id' => $user_id, 'video_id' => $video_id]);
            return ['success' => true, 'action' => 'added', 'message' => 'Vidéo ajoutée aux favoris.'];
        }
    }

    public function getUserFavoris($user_id) {
        try {
            $stmt = $this->pdo->prepare("SELECT p.id, p.name, p.image, p.video, p.price, p.type FROM favoris f JOIN products p ON f.video_id = p.id WHERE f.user_id = :user_id");
            $stmt->execute(['user_id' => $user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
    }
}
?>
