<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_carta'])) {
    $mitjaPilot = $_POST['mitjaPilot'];
    $nombrePiloto = $_POST['nombrePiloto'];
    $exp = $_POST['exp'];
    $rac = $_POST['rac'];
    $awa = $_POST['awa'];
    $pac = $_POST['pac'];

    $id_pais = $_POST['pais'];

    $imagenPiloto_tmp = $_FILES['imagenPiloto']['tmp_name'];
    $imagenPilotoName = $_FILES['imagenPiloto']['name'];
    $uploadPath = './media/' . $imagenPilotoName;
    move_uploaded_file($imagenPiloto_tmp, $uploadPath);

    try {
        // Insertar piloto en la tabla piloto
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

        // Obtener el idpiloto del piloto recién insertado
        $idpiloto = $conn->lastInsertId();

        // Insertar las relaciones entre el piloto y las competiciones seleccionadas en la tabla piloto_competicio
        $competiciones = $_POST['competiciones'];
        foreach ($competiciones as $idcompeticio) {
            $stmt = $conn->prepare("INSERT INTO piloto_competicio(idpiloto, idcompeticio) VALUES (:idpiloto, :idcompeticio)");
            $stmt->bindParam(':idpiloto', $idpiloto);
            $stmt->bindParam(':idcompeticio', $idcompeticio);
            $stmt->execute();
        }

        // Redirigir a la página principal después de la inserción
        header('Location: index.php');
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

$conn = null;
?>
