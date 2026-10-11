<?php
// get_materias.php
header('Content-Type: application/json');

// Configuración de conexión
$host = "localhost";
$user = "root";
$pass = "";
$db = "secretaria";

// Lista blanca de pensums permitidos
$permitidos = [
    'pensum_ads',
    'pensum_civil', 
    'pensum_desastres',
    'pensum_economia',
    'pensum_enfermeria'
];

try {
    // Validar parámetro
    $pensum = $_GET['pensum'] ?? '';
    if (!in_array($pensum, $permitidos)) {
        throw new Exception("Pensum no válido");
    }

    // Conexión PDO
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta con parámetros seguros
    $stmt = $conn->prepare("SELECT id, codigo, materia, tipo_materia FROM $pensum");
    $stmt->execute();
    
    // Formatear respuesta
    $materias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($materias);

} catch(PDOException $e) {
    echo json_encode(['error' => "Error de base de datos: " . $e->getMessage()]);
} catch(Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
exit();
?>