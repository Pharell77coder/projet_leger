<?php
require_once 'Database.php';

class User {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function register($username, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        return $stmt->execute(['username' => $username, 'email' => $email, 'password' => $hashedPassword]);
    }

    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['username'];
            header("Location: index.php");
            exit();
        } else {
            return "Email ou mot de passe incorrect.";
        }
    }

    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUserProfile($id, $newUsername, $newEmail, $newPassword = null) {
        $sql = "UPDATE users SET username = :username, email = :email";
        $params = ['username' => $newUsername, 'email' => $newEmail, 'id' => $id];

        if ($newPassword) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $sql .= ", password = :password";
            $params['password'] = $hashedPassword;
        }

        $sql .= " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        $_SESSION['user'] = $newUsername;

        return "Profil mis à jour avec succès.";
    }
}
/*
require_once 'Database.php';

class User {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function register($username, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        return $stmt->execute(['username' => $username, 'email' => $email, 'password' => $hashedPassword]);
    }

    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['username'];
            header("Location: index.php");
            exit();
        } else {
            return "Email ou mot de passe incorrect.";
        }
    }
}*/

?>
