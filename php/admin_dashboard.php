<?php 
session_start();
require 'classes/Database.php';
require 'classes/Admin.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin.php");
    exit();
}

$admin = new Admin();
$tables = $admin->getTables();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau Administrateur</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Bienvenue Administrateur</h1>

    <h2>Liste des tables de la bases de données <?php echo $dbname; ?></h2>

    <?php 
    if ($tables) {
        foreach ($tables as $table) {
            $tableName = $table[0];
            echo "<h3>Table : $tableName </h3>";

            $stmt = $conn->query("SELECT * FROM $tableName");
            $rows = $stmt->fetchall(PDO::FETCH_ASSOC);

            if($rows) {
                echo "<table>";
                echo "<tr>";
                foreach (array_keys($rows[0]) as $column) {
                    echo "<th>$column</th>";
                }
                echo "<th>Actions</th>";
                echo "</tr>";

                foreach ($rows as $row) {
                    $idColumn = array_keys($rows[0])[0];

                    if ($row[$idColumn] == 0) {
                        continue;
                    }

                    echo "<tr>";
                    foreach ($row as $data) {
                        echo "<td>$data</td>";
                    }

                    echo "<td>";
                    echo "<a href='edit.php?table=$tableName&id={$row[$idColumn]}'>Modifier</a>";
                    echo "<a href='delete.php?table=$tableName&id={$row[$idColumn]} onclick='return confirm(\"Etes-vous sur de vouloir supprimer cet enregistrement ?\");>'Supprimer</a>";
                    echo "</td>";

                    echo "</tr>";
                }
                echo "</tables>";
            } else {
                echo "<p>Aucune donnée disponible dans la table $tableName .</p>";
            }
            echo "<p><a href='create.php?table=$tableName'>Ajouter un nouvel enregistrement</a></p>";
        }
    } else {
        echo "<p>Aucune table trouvée dans la base de données.</p>";
    }

    $conn = null;
    
    ?>
</body>
</html>