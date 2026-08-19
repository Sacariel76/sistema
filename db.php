<?php
// Database connection settings
$host = 'localhost';
$dbname = 'sistema';

// First try with root and no password
$username = 'root';
$password = '';
$conn = null;

try {
    // First connection attempt
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // If first attempt fails, try with alternative credentials
    try {
        $username = 'conexion';
        $password = 'Seguridad2026.';
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e2) {
        die("No se pudo conectar a la base de datos: " . $e2->getMessage());
    }
}
?>
