<?php
header('Content-Type: application/json');

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'secretaria';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Obtener y validar parámetros
    $carrera = $_POST['carrera'] ?? '';
    $cedula = $_POST['cedula'] ?? '';
    
    // Validar formato de cédula y carrera
    $tablasPermitidas = ['ads', 'desastres', 'economia', 'enfermeria', 'ingenieria_civil'];
    
    if (!in_array($carrera, $tablasPermitidas)) {
        throw new Exception('Carrera no válida');
    }

    // Normalizar cédula manteniendo el formato almacenado
    $cedula = strtoupper($cedula);
    $cedula = preg_replace('/[^VE0-9.-]/', '', $cedula);
    $cedula = preg_replace('/([VE])\-?(\d{2})\.?(\d{3})\.?(\d{3})/', '$1-$2.$3.$4', $cedula);

    // Consulta segura usando prepared statements
    $stmt = $conn->prepare("SELECT nombre, apellidos FROM $carrera WHERE cedula = :cedula");
    $stmt->bindParam(':cedula', $cedula, PDO::PARAM_STR);
    $stmt->execute();
    
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultado) {
        echo json_encode([
            'nombre' => $resultado['nombre'],
            'apellidos' => $resultado['apellidos']
        ]);
    } else {
        echo json_encode(['error' => 'Estudiante no encontrado']);
    }
    
} catch(PDOException $e) {
    error_log("Error de base de datos: " . $e->getMessage());
    echo json_encode(['error' => 'Error en la consulta']);
} catch(Exception $e) {
    error_log("Error de validación: " . $e->getMessage());
    echo json_encode(['error' => $e->getMessage()]);
}
?>