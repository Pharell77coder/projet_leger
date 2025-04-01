<?php
require_once 'Database.php';

class UserProfileManager {
    private $pdo;
    private $user;

    public function __construct($username) {
        $this->pdo = Database::getInstance()->getConnection();
        $this->loadUser($username);
    }

    private function loadUser($username) {
        $stmt = $this->pdo->prepare("SELECT id, username, email FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $this->user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$this->user) {
            die("Utilisateur introuvable.");
        }
    }

    public function updateUserProfile($newUsername, $newEmail, $newPassword = null) {
        $sql = "UPDATE users SET username = :username, email = :email" . ($newPassword ? ", password = :password" : "") . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':username', $newUsername);
        $stmt->bindParam(':email', $newEmail);
        
        if ($newPassword) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $hashedPassword);
        }

        $stmt->bindParam(':id', $this->user['id']);
        $stmt->execute();

        $_SESSION['username'] = $newUsername;
        $_SESSION['email'] = $newEmail;

        return "Informations mises à jour avec succès.";
    }

    public function getUser() {
        return $this->user;
    }
}
?>
