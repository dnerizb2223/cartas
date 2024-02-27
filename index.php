<?php
require_once 'config.php'; // Incluye el archivo de configuración de la base de datos

// Obtén todas las cartas de la base de datos con información sobre el país del piloto
try {
    $stmt = $conn->query("SELECT piloto.*, pais.bandera, pais.nombre AS nombre_pais, competicio.imagen_competencia
                    FROM piloto
                    JOIN pais ON piloto.idpais = pais.idpais
                    JOIN piloto_competicio ON piloto.idpiloto = piloto_competicio.idpiloto
                    JOIN competicio ON piloto_competicio.idcompeticio = competicio.idcompeticio");
$cartas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}


// Borrar carta si se ha enviado una solicitud de borrado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['borrar_carta'])) {
    $cartaId = $_POST['carta_id'];

    try {
        $stmt = $conn->prepare("DELETE FROM piloto WHERE idpiloto = :cartaId");
        $stmt->bindParam(':cartaId', $cartaId);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Error al borrar la carta: " . $e->getMessage();
    }
}

// Cerrar la conexión a la base de datos
$conn = null;
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
    <nav>
        <ul>
            <li><a href="./index.php">Inici</a></li>
            <li><a href="./crear-carta.php">Crear Carta</a></li>
        </ul>
    </nav>
    <div class="cartas-container">
        <?php
        if (!empty($cartas)) {
            foreach ($cartas as $carta) {
                echo "<div class='card text-bg-dark'>";
                
                // Verifica si el nombre está definido
                if (!empty($carta['name'])) {
                    echo "<h5 class='nombreCarta'>{$carta['name']}</h5>";
                }

                if (!empty($carta['media'])) {
                    echo "<h5 class='mediaCarta'>{$carta['media']}</h5>";
                }

                if (!empty($carta['nombre'])) {
                    echo "<h5 class='nombrePaisCarta'>Pais: {$carta['nombre']}</h5>";
                }
                if (!empty($carta['bandera'])) {
                    echo "<img src='{$carta['bandera']}' class='bandera-card' alt='Bandera del Piloto'>";
                }
                if (!empty($carta['imagen_competencia'])) {
                    echo "<img src='{$carta['imagen_competencia']}' class='competencia-img' alt='Imagen de la Competición'>";
                }
                // Verifica si el promedio está definido
                echo "<div class='atributosCarta'>";
                if (!empty($carta['exp'])) {
                    echo "<h5 class='expCarta'>Exp: {$carta['exp']}</h5>";
                }
                if (!empty($carta['rac'])) {
                    echo "<h5 class='racCarta'>Rac: {$carta['rac']}</h5>";
                }
                if (!empty($carta['awa'])) {
                    echo "<h5 class='awaCarta'>Awa: {$carta['awa']}</h5>";
                }
                if (!empty($carta['pac'])) {
                    echo "<h5 class='pacCarta'>Pac: {$carta['pac']}</h5>";
                }
                echo "</div>";

                echo "<div class='card-img-overlay'>";
                echo "<div class='stats'>";

                 // Botón de edición
                echo "<a href='editar-carta.php?id={$carta['idpiloto']}' class='btn btn-primary editar-btn'>Editar</a>";

                
                // Botón para borrar la carta
                echo "<form method='POST' action='index.php'>";
                echo "<input type='hidden' name='carta_id' value='{$carta['idpiloto']}'>";
                echo "<button type='submit' name='borrar_carta' class='btn btn-danger borrar-btn'>Borrar</button>";
                echo "</form>";

                // Puedes mostrar más información aquí según sea necesario
                echo "</div>";
                echo "</div>";

                // Verifica si la imagen está definida
                if (!empty($carta['photo'])) {
                    echo "<img src='{$carta['photo']}' class='card-img' alt='Imagen del Piloto'>";
                }

                echo "</div>";
            }
        } else {
            echo "No hay cartas en la base de datos.";
        }
        ?>
    </div>
</body>
</html>
