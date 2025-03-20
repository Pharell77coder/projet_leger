<?php 
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin.php");
    exit();
}

include 'bdd.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['table']) || isset($_GET['table'])) {
        $table = $_POST['table'] ?? $_GET['table'];

        $stmt = $conn->query("DESCRIBE $table");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            unset($_POST['submit'], $_POST['table']);

            if (!empty($_POST)) {
                $columnsNames = array_keys($_POST);
                $placeholders = array_map(fn($col) => ":$col", $columnsNames);

                foreach ($_POST as $key => $value) {
                    $date_array = ['date_added', 'upload_date'];  
                    if (in_array($key, $date_array)) { 
                        if (strtotime($value) === false || empty($value)) {  
                            $_POST[$key] = date('Y-m-d');
                        }
                    }
                    
                    $date_array = ['date_ajout', 'order_date'];  
                    if (in_array($key, $date_array)) { 
                        if (strtotime($value) === false || empty($value)) { 
                            $_POST[$key] = date('Y-m-d H:i:s', strtotime('+1 minutes')); 
                        }
                    }
                    
                    
                    

                    if ($key == 'password' && !empty($value)) {
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
                echo "Aucune donnée fournie.";
            }
        }
    } else {
        echo "Table non spécifiée.";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un nouvel enregistrement</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Ajouter un nouvel enregistrement dans la table <?php echo htmlspecialchars($table); ?></h1>

    <form method="post" action="create.php">
        <input type="hidden" name="table" value="<?php echo htmlspecialchars($table); ?>">
        <?php 
        foreach ($columns as $column) {
            if ($column['Field'] != 'id') {  
                echo "<label for='{$column['Field']}'>" . ucfirst($column['Field']) . " :</label>";
                echo "<input type='text' id='{$column['Field']}' name='{$column['Field']}' required><br>";
            }
        }
        ?>
        <button type="submit" name="submit">Ajouter</button>
    </form>

    <p><a href="admin_dashboard.php">Retourner à l'administration</a></p>
</body>
</html>
