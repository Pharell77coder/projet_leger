<?php 
session_start();
require_once 'classes/Database.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin.php");
    exit();
}

try {
    $conn = Database::getInstance()->getConnection();
    if (isset($_POST['table']) || isset($_GET['table'])) {
        $table = $_POST['table'] ?? $_GET['table'];

        $stmt = $conn->prepare("DESCRIBE $table");
        $stmt->execute();
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            unset($_POST['submit'], $_POST['table']);

            if (!empty($_POST)) {
                $columnsNames = array_keys($_POST);
                $placeholders = array_map(fn($col) => ":$col", $columnsNames);

                foreach ($_POST as $key => $value) {
                    $date_fields = ['date_added', 'upload_date', 'date_ajout', 'order_date'];
                    if (in_array($key, $date_fields) && (empty($value) || strtotime($value) === false)) {
                        $_POST[$key] = in_array($key, ['date_ajout', 'order_date']) ? date('Y-m-d H:i:s', strtotime('+1 minute')) : date('Y-m-d');
                    }

                    if ($key === 'password' && !empty($value)) {
                        $_POST[$key] = password_hash($value, PASSWORD_DEFAULT);
                    }
                }

                $sql = "INSERT INTO $table (" . implode(", ", $columnsNames) . ") VALUES (" . implode(", ", $placeholders) . ")";
                $stmt = $conn->prepare($sql);

                foreach ($_POST as $key => $value) {
                    $stmt->bindValue(":$key", $value);
                }

                $stmt->execute();
                header("Location: admin_dashboard.php?table=$table&success=1");
                exit();
            } else {
                echo "<p style='color: red;'>Aucune donnée fournie.</p>";
            }
        }
    } else {
        echo "<p style='color: red;'>Table non spécifiée.</p>";
    }
} catch (PDOException $e) {
    echo "<p style='color: red;'>Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un nouvel enregistrement</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/connexion.css">
</head>
<body>
<?php include 'navbar.php'; ?>
    <h1>Ajouter un nouvel enregistrement dans la table <?php echo htmlspecialchars($table); ?></h1>

    <form method="post" action="create.php">
        <input type="hidden" name="table" value="<?php echo htmlspecialchars($table); ?>">
        <?php 
        foreach ($columns as $column) {
            if ($column['Field'] !== 'id') {  
                echo "<label for='{$column['Field']}'>" . ucfirst($column['Field']) . " :</label>";
                echo "<input type='text' id='{$column['Field']}' name='{$column['Field']}' required><br>";
            }
        }
        ?>
        <button type="submit" name="submit">Ajouter</button>
    </form>

    <p><a href="admin_dashboard.php">Retourner à l'administration</a></p>
    <?php include 'footer.php'; ?>
</body>
</html>
