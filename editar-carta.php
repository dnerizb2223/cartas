<?php
require_once 'config.php';

if(isset($_GET['id'])) {
    $cartaId = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM piloto WHERE idpiloto = :cartaId");
    $stmt->bindParam(':cartaId', $cartaId);
    $stmt->execute();
    $carta = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_edicion'])) {
        $nombre = $_POST['nombre'];
        $media = $_POST['media'];
        $exp = $_POST['exp'];
        $rac = $_POST['rac'];
        $awa = $_POST['awa'];
        $pac = $_POST['pac'];

        try {
            $stmt = $conn->prepare("UPDATE piloto SET name = :nombre, media = :media, exp = :exp, rac = :rac, awa = :awa, pac = :pac WHERE idpiloto = :cartaId");
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':media', $media);
            $stmt->bindParam(':exp', $exp);
            $stmt->bindParam(':rac', $rac);
            $stmt->bindParam(':awa', $awa);
            $stmt->bindParam(':pac', $pac);
            $stmt->bindParam(':cartaId', $cartaId);
            $stmt->execute();

            header('Location: index.php');
            exit();
        } catch (PDOException $e) {
            echo "Error al guardar la edición: " . $e->getMessage();
        }
    }
} else {
    echo "No se proporcionó un ID de carta válido.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Cartas</title>
    <meta name="description" content="Cartas">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<body>
    <?php if(isset($carta)): ?>
    <form method="POST" class="carta" action="editar-carta.php?id=<?php echo $carta['idpiloto']; ?>">
        <input type="hidden" name="carta_id" value="<?php echo $carta['idpiloto']; ?>">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" value="<?php echo $carta['name']; ?>"><br>
        <label for="media">Media:</label>
        <input type="text" name="media" value="<?php echo $carta['media']; ?>"><br>
        <label for="exp">Experiencia:</label>
        <input type="text" name="exp" value="<?php echo $carta['exp']; ?>"><br>
        <label for="rac">Reflejos:</label>
        <input type="text" name="rac" value="<?php echo $carta['rac']; ?>"><br>
        <label for="awa">Pas per Curves:</label>
        <input type="text" name="awa" value="<?php echo $carta['awa']; ?>"><br>
        <label for="pac">Velocidad:</label>
        <input type="text" name="pac" value="<?php echo $carta['pac']; ?>"><br>
        <input type="submit" name="guardar_edicion" value="Guardar Cambios">
    </form>
    <?php endif; ?>
</body>
</html>