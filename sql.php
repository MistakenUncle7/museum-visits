<?php

// Datos de conexión
$host = 'localhost';
$user = 'root'; 
$password = ''; 
$database = 'panteras';

// Crear conexión
$conn = new mysqli($host, $user, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}
?>
