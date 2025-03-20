<?php
session_start();
include 'bdd.php';

date_default_timezone_set('Europe/Paris');

if (!isset($_SESSION['username'])) {
    echo "Utilisateur non connecté.";
    exit;
}

$username = $_SESSION['username'];

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "Utilisateur introuvable.";
        exit;
    }

    $user_id = $user['id'];

    $stmt = $pdo->prepare("
        SELECT o.id AS order_id, o.total_price, o.order_date, o.status, p.name AS product_name, p.image AS product_image
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN products p ON oi.product_id = p.id
        WHERE o.user_id = ? AND o.status = 1
        ORDER BY o.order_date DESC
    ");
    
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

// Convertir les dates au bon fuseau horaires
foreach ($orders as &$order) {
    $date = new DateTime($order['order_date'], new DateTimeZone('UTC'));
    $date->setTimezone(new DateTimeZone('Europe/Paris'));
    $order['order_date'] = $date->format('Y-m-d H:i:s');
}

// Récupérer les commandes payées non encore envoyées par email
$paidorders = array_filter($orders, function($order) {
    return $order['status'] == 1 && $order['email_order'] == 0; // Status payé = 1 et email_order = 0
});

// Vérifier s'il y a des commandes à envoyer par email
if (!empty($paidorders)) {
    $to = "test@example.com";
    $subject = "Votre commande a été validée !";

    // Construire le message HTML
    $message = "<html><head><title>Confirmation de commande</title></head><body>";
    $message .= "<h2>Merci pour votre commande !</h2>";
    $message .= "<table border='1' cellspacing='0' cellpadding='5'>";
    $message .= "<tr><th>Nom</th><th>Prix</th><th>Date</th></tr>";

    $totalAmount = 0;

    foreach ($paidorders as $order) {
        $message .= "<tr>";
        $message .= "<td>" . htmlspecialchars($order['name']) . "</td>";
        $message .= "<td>" . number_format($order['total_price'], 2, ',', ' ') . "€</td>";
        $message .= "<td>" . htmlspecialchars($order['order_date']) . "</td>";
        $message .= "</tr>";

        $totalAmount += $order['total_price'];
    }
    $message .= "</table>";
    $message .= "<h3>Total : " . number_format($totalAmount, 2, '.', ' ') . "€</h3>";
    $message .= "<p>Votre commande est en cours de traitement.</p>";
    $message .= "</body></html>";
    
    // En têtes de l'email
    $headers = "From: no-reply@" . $_SERVER['HTTP_HOST'] . "\r\n";
    $headers .= "Reply-To: support@" . $_SERVER['HTTP_HOST'] . "\r\n";
    $headers = "MIME-Version: 1.0\r\n";
    $headers = "Content-Type: text/html; charset=UTF-8\r\n";
    
    // Envoi de l'email
    if (mail($to, $subject, $message, $headers)) {
        echo "<p style='color: green;'>E-mail de confirmation envoyé avec succès.</p>";
    
        // Mettre à jour email_order à 1 pour les commandes envoyées
        foreach ($paidorders as $order) {
            $updateStmt = $pdo->prepare("UPDATE orders SET email_order = 1 WHERE id = ?");
            $updateStmt->execute([$order['id']]);
        }
    } else {
        echo "<p style='color: red;'>Erreur lors de l'envoi de l'email.</p>";
    }
    
}



?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des commandes</title>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <h1>Historique des commandes</h1>
    <?php if (!empty($orders)) : ?>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Nom du produit</th>
                    <th>Total</th>
                    <th>Statut</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($order['total_price']); ?> €</td>
                    <td>
                        <?php
                        switch ($order['status']) {
                            case 1:
                                echo 'Payé';
                                break;
                            case 2:
                                echo 'Annulé';
                                break;
                            case 3:
                                echo 'En attente';
                                break;                       
                        }
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Vous n'avez aucune commande payée</p>
    <?php endif; ?>
    <a href="../index.php">Retourner au catalogue</a>
    <?php include 'footer.php'; ?>
</body>
</html>
