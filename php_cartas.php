<?php
require_once 'config.php'; // Incluye el archivo de configuración de la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_carta'])) {
    // Recopila los datos del formulario, incluyendo idpais y bandera
    $mitjaPilot = $_POST['mitjaPilot'];
    $nombrePiloto = $_POST['nombrePiloto'];
    $exp = $_POST['exp'];
    $rac = $_POST['rac'];
    $awa = $_POST['awa'];
    $pac = $_POST['pac'];

    // Procesa la imagen del Piloto (asegúrate de manejar las subidas de archivos de manera segura)
    $imagenPiloto_tmp = $_FILES['imagenPiloto']['tmp_name'];
    $imagenPilotoName = $_FILES['imagenPiloto']['name'];
    $uploadPath = './media/' . $imagenPilotoName;
    move_uploaded_file($imagenPiloto_tmp, $uploadPath);

    // Procesa la bandera del País (asegúrate de manejar las subidas de archivos de manera segura)
    $bandera_tmp = $_FILES['bandera']['tmp_name'];
    $banderaName = $_FILES['bandera']['name'];
    $uploadPathBandera = './banderas/' . $banderaName;
    move_uploaded_file($bandera_tmp, $uploadPathBandera);

    // Inserta los datos en la base de datos
    try {
        $stmt1 = $conn->prepare("INSERT INTO pais(nombre, bandera) VALUES (:nombre, :bandera)");
        $stmt1->bindParam(':nombre', $_POST['paisPiloto']);
        $stmt1->bindParam(':bandera', $uploadPathBandera);
        $stmt1->execute();
        $id_pais = $conn->lastInsertId();

        $stmt = $conn->prepare("INSERT INTO piloto(media, name, exp, rac, awa, pac, photo, idpais) VALUES (:mitjaPilot, :name, :exp, :rac, :awa, :pac, :photo, :idpais)");
        $stmt->bindParam(':mitjaPilot', $mitjaPilot);
        $stmt->bindParam(':name', $nombrePiloto);
        $stmt->bindParam(':exp', $exp);
        $stmt->bindParam(':rac', $rac);
        $stmt->bindParam(':awa', $awa);
        $stmt->bindParam(':pac', $pac);
        $stmt->bindParam(':photo', $uploadPath);
        $stmt->bindParam(':idpais', $id_pais);
        $stmt->execute();

        // Obtener el ID del piloto recién insertado
        $id_piloto = $conn->lastInsertId();

        // Insertar las competiciones seleccionadas
        if (isset($_POST['competicio']) && is_array($_POST['competicio'])) {
            foreach ($_POST['competicio'] as $competicio_id) {
                // Verificar la existencia de la competición
                $stmt_check = $conn->prepare("SELECT COUNT(*) AS count FROM competicio WHERE idcompeticio = :idcompeticio");
                $stmt_check->bindParam(':idcompeticio', $competicio_id);
                $stmt_check->execute();
                $competencia_count = $stmt_check->fetchColumn();

                if ($competencia_count > 0) {
                    // Insertar el ID del piloto y el ID de la competencia en la tabla piloto_competicio
                    $stmt2 = $conn->prepare("INSERT INTO piloto_competicio(idpiloto, idcompeticio) VALUES (:idpiloto, :idcompeticio)");
                    $stmt2->bindParam(':idpiloto', $id_piloto);
                    $stmt2->bindParam(':idcompeticio', $competicio_id);
                    $stmt2->execute();
                } else {
                    // La competición con el ID dado no existe, manejar el error apropiadamente
                    echo "La competición con el ID $competicio_id no existe. No se pudo asociar al piloto.<br>";
                }
            }
        }


        $conn = null;

        header('Location: index.php'); // Redirige a la página principal después de agregar la carta
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Cierra la conexión a la base de datos
$conn = null;
?>
