<?php
session_start();

require_once 'classes/Database.php';

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    try {
        $pdo = Database::getInstance()->getConnection();

        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];

            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $user_od = $user['id'];
            } else {
                "Utilisateur non trouvé.";
            }
        } else {
            echo "Utilisateur non connecté.";
            exit;
        }
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
        $stmt->execute([$order_id, $user_id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($order) {
            $stmt = $pdo->prepare("UPDATE orders SET status = 1 WHERE id = ?");
            $stmt->execute([$order_id]);
    
            if ($stmt->rowCount() > 0) {
                echo "La commande avec l'ID $order_id a été validée avec succès.";
            } else {
                echo "Erreur lors de la validation de la commande.";
            }
        } else {
            echo "Commande non trouvée ou vous n'êtes pas autorisé à la valider.";
        }
    
        header("Location: order_history.php");
        exit;
    } catch (PDOException $e) {
        echo "Erreur lors de la mise à jour du statut : " . $e->getMessage();
    }
} else {
    echo "ID de commande manquant.";
    exit;
}
?>