<?php
session_start();
include 'bdd.php';

if (!isset($_SESSION['username'])) {
    echo "Utilisateur non connecté.";
    exit;
}

$username = $_SESSION['username'];

try {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "Utilisateur introuvable.";
        exit;
    }

    $user_id = $user['id'];

    $stmt = $pod->prepare("
        SELECT o.id AS order_id, o.total_price, o.order_date, o.status, p.name AS product_name, p.image AS product_image
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN products p ON oi.product_id = p.id
        WHERE o.user_id = ? AND o.status = 1
        ORDER BY o.order_date DESC
    ");

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
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
                <?php foreach ($oders as $order): ?>
            <td><img src="../<?php echo $order['product_image']; ?>" alt="<?php echo $order['product_image']; ?>" width="100"></td>
            <td><?php echo $order['product_name']; ?></td>
            <td><?php echo $order['total_price']; ?> €</td>
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
        <td><?php echo htmlspecialchars($order['order_data'])?></td>
        <?php endforeach ?>
            </tbody>
        </table>
        <?php else: ?>
            <p>Vous n'avez aucune commande payée</p>
        <?php endif; ?>
        <a href="../index.php">Reourner au catalogue</a>
    <?php include 'footer.php'; ?>
</body>
</html>