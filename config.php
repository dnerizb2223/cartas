<?php
$servername = "localhost";
$username = "root";
$password = "Dn20032003";
$dbname = "carta";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Obtener todas las competiciones
    $stmt = $conn->query("SELECT * FROM competicio");
    $competiciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Obtener todos los países
    $stmt = $conn->query("SELECT * FROM pais");
    $paisos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
