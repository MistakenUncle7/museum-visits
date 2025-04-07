<?php 

include 'sql.php'; 

// Get the JSON input
$filters = json_decode(file_get_contents("php://input"), true);

// Initialize the base query
$query = "SELECT visitas.sexo, visitas.edad, p1.Nombre AS residencia, p2.Nombre
    AS nacionalidad, e.Grado AS escolaridad, visitas.estado_escolar, l1.No
    mbre AS primera_leng, l2.Nombre AS segunda_leng, f.Rango AS frecuencia
    _visita, m.Motivo AS motivo, t.Nombre AS medio_transporte, visitas.tie
    mpo_traslado, r.Nombre AS tipo_grupo, visitas.tamano_grupo, visitas.me
    nores_grupo FROM visitas INNER JOIN pais p1 ON visitas.residencia = p1
    .ID INNER JOIN pais p2 ON visitas.nacionalidad = p2.ID INNER JOIN esco
    laridad e ON visitas.escolaridad = e.ID INNER JOIN lenguaje l1 ON visi
    tas.primera_leng = l1.ID INNER JOIN lenguaje l2 ON visitas.segunda_len
    g = l2.ID INNER JOIN frec_visita f ON visitas.frecuencia_visita = f.ID
    INNER JOIN motivos m ON visitas.motivo = m.ID INNER JOIN transporte t
    ON visitas.medio_transporte = t.ID INNER JOIN relacion r ON visitas.t
    ipo_grupo = r.ID";

// Initialize the WHERE clause
$whereClauses = ["1=1"];

// Add conditions based on the filters
if (!empty($filters['start'])) {
    $whereClauses[] = "fecha_reg >= '" . $conn->real_escape_string($filters['start']) . "'";
}
if (!empty($filters['end'])) {
    $whereClauses[] = "fecha_reg <= '" . $conn->real_escape_string($filters['end']) . "'";
}
if (!empty($filters['motives'])) {
    $whereClauses[] = "motivos.Nombre = '" . $conn->real_escape_string($filters['motives']) . "'";
}
if (!empty($filters['nationality'])) {
    $whereClauses[] = "p2.Nombre = '" . $conn->real_escape_string($filters['nationality']) . "'";
}
if (!empty($filters['residence'])) {
    $whereClauses[] = "p1.Nombre = '" . $conn->real_escape_string($filters['residence']) . "'";
}
if (!empty($filters['language'])) {
    $whereClauses[] = "l1.Nombre = '" . $conn->real_escape_string($filters['language']) . "'";
}
if (!empty($filters['freq'])) {
    $whereClauses[] = "f.Rango = '" . $conn->real_escape_string($filters['freq']) . "'";
}
if (!empty($filters['studies'])) {
    $whereClauses[] = "e.Grado = '" . $conn->real_escape_string($filters['studies']) . "'";
}

// Combine the WHERE clauses
$whereClause = implode(" AND ", $whereClauses);

// Query 1: Total number of visits
$totalVisits = "
    SELECT COUNT(*) AS total_visits
    FROM visitas
    INNER JOIN pais p1 ON visitas.residencia = p1.ID
    INNER JOIN pais p2 ON visitas.nacionalidad = p2.ID
    INNER JOIN motivos ON visitas.motivo = motivos.ID
    INNER JOIN lenguaje l1 ON visitas.primera_leng = l1.ID
    INNER JOIN frec_visita f ON visitas.frecuencia_visita = f.ID
    INNER JOIN escolaridad e ON visitas.escolaridad = e.ID
    WHERE $whereClause";
    $totalVisitsResult = $conn->query($totalVisits);
$totalVisits = $totalVisitsResult->fetch_assoc()['total_visits'] ?? 0;

// Query 2: Number of visits from Mexicans (nationality = Mexico)
$nationalVisits = "
    SELECT COUNT(*) AS national_visits
    FROM visitas
    INNER JOIN pais p2 ON visitas.nacionalidad = p2.ID
    WHERE $whereClause AND p2.Nombre = 'Mexico'";
$mexicanVisitsResult = $conn->query($nationalVisits);
$mexicanVisits = $mexicanVisitsResult->fetch_assoc()['national_visits'] ?? 0;

// Query 3: Number of visits from foreigners (nationality != Mexico)
$foreignVisits = "
    SELECT COUNT(*) AS foreign_visits
    FROM visitas
    INNER JOIN pais p2 ON visitas.nacionalidad = p2.ID
    WHERE $whereClause AND p2.Nombre != 'Mexico'";
$foreignVisitsResult = $conn->query($foreignVisits);
$foreignVisits = $foreignVisitsResult->fetch_assoc()['foreign_visits'] ?? 0;


// Query 4: Most spoken language
$mostSpokenLanguage = "
    SELECT l1.Nombre AS most_spoken_language
    FROM visitas
    INNER JOIN lenguaje l1 ON visitas.primera_leng = l1.ID
    GROUP BY l1.Nombre
    ORDER BY COUNT(*) DESC
    LIMIT 1";
$mostSpokenLanguageResult = $conn->query($mostSpokenLanguage);
$mostSpokenLanguage = $mostSpokenLanguageResult->fetch_assoc()['most_spoken_language'] ?? "N/A";

// Query 5: Most frequent reason
$mostFrequentReason = "
    SELECT motivos.Motivo AS most_frequent_reason
    FROM visitas
    INNER JOIN motivos ON visitas.motivo = motivos.ID
    GROUP BY motivos.Motivo
    ORDER BY COUNT(*) DESC
    LIMIT 1";
$mostFrequentReasonResult = $conn->query($mostFrequentReason);
$mostFrequentReason = $mostFrequentReasonResult->fetch_assoc()['most_frequent_reason'] ?? "N/A";

// Combine all results into a single response
$response = [
    "total_visits" => $totalVisits,
    "foreign_visits" => $foreignVisits,
    "national_visits" => $mexicanVisits,
    "most_spoken_language" => $mostSpokenLanguage,
    "most_frequent_reason" => $mostFrequentReason
];

// Return the results as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>

