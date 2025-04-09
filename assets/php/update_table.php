<?php

include "sql.php";

// Get the JSON filt$filters
$input = json_decode(file_get_contents("php://input"), true);

// Initialize the base query
$query = "
    SELECT visitas.sexo, visitas.edad, p1.Nombre AS residencia, p2.Nombre
    AS nacionalidad, e.Grado AS escolaridad, visitas.estado_escolar, l1.Nombre 
    AS primera_leng, l2.Nombre AS segunda_leng, f.Rango AS frecuencia_visita,
    m.Motivo AS motivo, t.Nombre AS medio_transporte, visitas.tiempo_traslado,
    r.Nombre AS tipo_grupo, visitas.tamano_grupo, visitas.menores_grupo 
    FROM visitas 
    INNER JOIN pais p1 ON visitas.residencia = p1.ID 
    INNER JOIN pais p2 ON visitas.nacionalidad = p2.ID 
    INNER JOIN escolaridad e ON visitas.escolaridad = e.ID 
    INNER JOIN lenguaje l1 ON visitas.primera_leng = l1.ID 
    INNER JOIN lenguaje l2 ON visitas.segunda_leng = l2.ID 
    INNER JOIN frec_visita f ON visitas.frecuencia_visita = f.ID
    INNER JOIN motivos m ON visitas.motivo = m.ID 
    INNER JOIN transporte t ON visitas.medio_transporte = t.ID 
    INNER JOIN relacion r ON visitas.tipo_grupo = r.ID 
    $input
";
$result = $conn->query($query);
if (!$result) {
    echo json_encode(["error" => $conn->error]);
    exit;
}

$finalResult = [];
while ($row = $result->fetch_assoc()) {
    $finalResult[] = $row;
}

// Return the results as JSON
header('Content-Type: application/json');
echo json_encode($finalResult);

?>