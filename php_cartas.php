<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_carta'])) {
    $mitjaPilot = $_POST['mitjaPilot'];
    $nombrePiloto = $_POST['nombrePiloto'];
    $exp = $_POST['exp'];
    $rac = $_POST['rac'];
    $awa = $_POST['awa'];
    $pac = $_POST['pac'];

    $id_pais =$_POST['pais'];

    $imagenPiloto_tmp = $_FILES['imagenPiloto']['tmp_name'];
    $imagenPilotoName = $_FILES['imagenPiloto']['name'];
    $uploadPath = './media/pilots/' . $imagenPilotoName;
    move_uploaded_file($imagenPiloto_tmp, $uploadPath);

    try {
      

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

        $conn = null;

        header('Location: index.php');
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

$conn = null;
?>