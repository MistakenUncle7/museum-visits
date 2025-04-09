<?php

// Displays connection errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conection data
$host = 'localhost';
$user = 'root'; 
$password = 'root'; // In Wamp it is ''
$database = 'panteras';

// Create conection
$conn = new mysqli($host, $user, $password, $database);

// Verify conection
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

// Check if there are results
function getOptions($result, $colName) {
    if ($result->num_rows > 0) {
        // Loop through the results and create <option> elements
        while($row = $result->fetch_assoc()) {
            echo "<option value='".$row[$colName]."'>".$row[$colName]."</option>";
        }
    } else {
        echo "<option value=''>No hay resultados</option>";
    }
}

?>