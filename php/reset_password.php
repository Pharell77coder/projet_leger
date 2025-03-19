<?php

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe oublié</title>
</head>
<body>
<div class="login-container">
    <div class="form-container">
        <h2>Réinitialisation du mot de passe</h2>
        <form action="reset_password.php" method="POST">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token); ?>">
            <label for="new_password">Nouveau mot de passe :</label>
            <input type="password" id="new_password" name="new_password" required>
            <label for="confirm_password">Confirmer le mot de passe :</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <button type="submit">Réinitialiser</button>
        </form>
    </div>
</div>
</body>
</html>