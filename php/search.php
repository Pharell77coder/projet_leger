<?php
include 'bdd.php';

if (isset($_GET['query'])) {
    $search = htmlspecialchars($_GET['query']);

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=$dbname", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }

    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE :query");
    $stmt->execute(['query' => '%' . $search . '%']);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de recherche</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/product.css">
</head>
<body>
   <?php include 'navbar.php' ?> 
        <h1>Résultats pour "<?= htmlspecialchars($search) ?>"</h1>
        <div class="product-list">
        <?php if(!empty($results)): ?>
            <?php foreach ($results as $product): ?>
            <div class="product-item">
                <img src="../<?=htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
            
            <!--<video controls>
                <source src="../<?= htmlspecialchars($product['video']); ?>" type="video/mp4">
                <?php //$_COOKIE ?>Votre navigateur ne supporte pas les vidéos.
            </video>-->

            <h2>
                <a href="video.php?id=<?= htmlspecialchars($product['id']); ?>">
                    <?= htmlspecialchars($product['name']); ?>
                </a>
            </h2>
            <p>Prix: <?= htmlspecialchars($product['price']); ?>€</p>

            <form method="post" action="add_to_cart.php">
                <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']); ?>">
                <button type="submit" name="add_to_cart">Ajouter au Panier</button>
            </form>
        </div>
    <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun résultat trouvé pour votre recherche.</p>
    <?php endif; ?>
</div>
   <?php include 'footer.php' ?> 
</body>
</html>