<?php 

if ($_SERVER['HTTP_HOST'] === 'localhost') {
    $base_url = "/projet_leger/"; 
} else {
    $base_url = "/";
}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Footer</title>
        <link rel="stylesheet" href="<?php echo $base_url; ?>css/footer.css">
    </head>
    <body>
        <footer class="footer">
        <p>&copy; <?php echo date('Y');?> Pharell T. | Tous droits réservés.</p>
        <p>
            <a href="<?php echo $base_url; ?>private.php">Politique de confidentialité.</a>
            <a href="<?php echo $base_url; ?>terms.php">Conditions d'utilisation</a>
        </p>
        </footer>
    </body>
</html>
