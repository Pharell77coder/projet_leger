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

    if(isset($_GET['table']) && isset($_GET['id'])) {
        $table = $_GET['table'];
        $id = $_GET['id'];

        $stmt = $conn->prepare("SELECT * FROM $table WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$row) {
            echo "Aucun enregistrement trouvé avec cet ID.";
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $columns = $_POST['columns'];


            $setClause = [];
            foreach($columns as $column => $value) {
                if ($column !== 'password') {
                    $setClause[] = "$column = :$column";
                }
            }
            $setClause = implode(',',$setClause);
            
            $stmt = $conn ->prepare("UPDATE $table SET $setClause WHERE id = :id");
            foreach ($columns as $column => $value) {
                if ($column !== 'password') {
                    $stmt->bindValue(":$column", $value); // bindParam fonctinonne pas
                }
            }
            $stmt->bindParam(':id',$id);
            $stmt->execute();

            header("Location: admin_dashboard.php");
            exit();

        }
    }
} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'enregistrement</title>
</head>
<body>

    <h2>Modifie l'enregistrement dans la table"<?php echo $table;?>"</h2>

    <form action="" method="POST">
        <?php foreach ($row as $column => $value): ?>
        <?php if ($column !== 'password'): // Exclure le mot de passe?>
        <label for="<?php echo $column; ?>"><?php echo $column;?> :</label>
        <input type="text" name="columns[<?php echo $column; ?>]" value="<?php echo $value;?>" id="<?php echo $column;?>">
        <br>
        <?php endif; ?>
        <?php endforeach; ?>
        <input type="submit" value="Enregistrer les modifications">
    </form>

</body>
</html>