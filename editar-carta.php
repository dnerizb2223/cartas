<?php
require_once 'config.php';

if(isset($_GET['id'])) {
    $cartaId = $_GET['id'];

    // Obtener información de la carta desde la base de datos
    $stmt = $conn->prepare("SELECT piloto.*, GROUP_CONCAT(piloto_competicio.idcompeticio) AS idcompeticio FROM piloto LEFT JOIN piloto_competicio ON piloto.idpiloto = piloto_competicio.idpiloto WHERE piloto.idpiloto = :cartaId");
    $stmt->bindParam(':cartaId', $cartaId);
    $stmt->execute();
    $carta = $stmt->fetch(PDO::FETCH_ASSOC);

    // Obtener todas las competiciones
    try {
        $stmt = $conn->query("SELECT * FROM competicio");
        $competiciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_edicion'])) {
        // Obtener los datos actualizados del formulario
        $nombre = $_POST['nombre'];
        $media = $_POST['media'];
        $exp = $_POST['exp'];
        $rac = $_POST['rac'];
        $awa = $_POST['awa'];
        $pac = $_POST['pac'];
        $competicionesSeleccionadas = isset($_POST['competiciones']) ? $_POST['competiciones'] : [];

        try {
            // Actualizar los datos de la carta en la base de datos
            $stmt = $conn->prepare("UPDATE piloto SET name = :nombre, media = :media, exp = :exp, rac = :rac, awa = :awa, pac = :pac WHERE idpiloto = :cartaId");
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':media', $media);
            $stmt->bindParam(':exp', $exp);
            $stmt->bindParam(':rac', $rac);
            $stmt->bindParam(':awa', $awa);
            $stmt->bindParam(':pac', $pac);
            $stmt->bindParam(':cartaId', $cartaId);
            $stmt->execute();

            // Actualizar las relaciones entre la carta y las competiciones en la tabla de relación
            $stmt = $conn->prepare("DELETE FROM piloto_competicio WHERE idpiloto = :cartaId");
            $stmt->bindParam(':cartaId', $cartaId);
            $stmt->execute();

            // Insertar las nuevas relaciones seleccionadas en el formulario
            foreach ($competicionesSeleccionadas as $idcompeticio) {
                $stmt = $conn->prepare("INSERT INTO piloto_competicio(idpiloto, idcompeticio) VALUES (:cartaId, :idcompeticio)");
                $stmt->bindParam(':cartaId', $cartaId);
                $stmt->bindParam(':idcompeticio', $idcompeticio);
                $stmt->execute();
            }

            // Redirigir a la página principal después de guardar los cambios
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
    <title>Editar Carta</title>
    <meta name="description" content="Editar Carta">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<body>
    <nav>
        <ul>
            <li><a href="./index.php">Inici</a></li>
            <li><a href="./crear-carta.php">Crear Carta</a></li>
        </ul>
    </nav> 
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
        <label>Competiciones:</label><br>
        <?php foreach ($competiciones as $competicion): ?>
            <input type="checkbox" name="competiciones[]" value="<?php echo $competicion['idcompeticio']; ?>" <?php if(in_array($competicion['idcompeticio'], explode(',', $carta['idcompeticio']))): ?> checked <?php endif; ?>>
            <label><?php echo $competicion['nombre']; ?></label><br>
        <?php endforeach; ?>
        <input type="submit" name="guardar_edicion" value="Guardar Cambios">
    </form>
    <?php endif; ?>
</body>
</html>
