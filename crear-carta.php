<?php
require_once 'config.php';

try {
    $stmt = $conn->query("SELECT * FROM pais");
    $paisos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener todas las competiciones
    $stmt = $conn->query("SELECT * FROM competicio");
    $competiciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Crear Carta</title>
    <meta name="description" content="Crear Carta">
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
    <div class="carta">
        <form action="php_cartas.php" method="POST" enctype="multipart/form-data">
            <div class="infoForm">
                <div>
                    <label for="mitjaPilot">Mitja del Pilot:</label>
                    <input type="text" name="mitjaPilot"> <br>
                </div>
                <br>
                <div>
                    <label for="imagenPiloto">Foto del Pilot:</label>
                    <input type="file" name="imagenPiloto">
                </div>
                <br>
                <div>
                    <label for="nombrePiloto">Nom del Pilot:</label>
                    <input type="text" name="nombrePiloto">
                </div>      
            </div>
            <br>
            <div class="paisForm">
                <li>
                    <label for="pais">País:</label>
                    <select name="pais" id="pais">
                        <?php foreach ($paisos as $pais): ?>
                            <option value="<?php echo $pais["idpais"]; ?>"><?php echo $pais["nombre"]; ?></option>
                        <?php endforeach; ?>
                    </select>
                </li>
            </div>
            <br>
            <div>
                <label>Competiciones:</label><br>
                <?php foreach ($competiciones as $competicion): ?>
                    <input type="checkbox" name="competiciones[]" value="<?php echo $competicion['idcompeticio']; ?>">
                    <label><?php echo $competicion['nombre']; ?></label><br>
                <?php endforeach; ?>
            </div>
            <br>
            <div>
                <ul>
                    <li>
                        <label for="exp">Experiencia del Pilot:</label>
                        <input type="text" name="exp"><br>
                    </li>
                    <li>
                        <label for="rac">Reflexes del Pilot:</label>
                        <input type="text" name="rac"><br>
                    </li>
                    <li>
                        <label for="awa">Pas per Curves del Pilot:</label>
                        <input type="text" name="awa"><br>
                    </li>
                    <li>
                        <label for="pac">Velocitat del Pilot:</label>
                        <input type="text" name="pac"><br>
                    </li>
                </ul>
            </div>                
            <input type="submit" name="crear_carta" value="Enviar">
        </form>
    </div>
</body>
</html>
                        