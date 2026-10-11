<?php
header('Content-Type: application/json');

// Configuración de la base de datos (usar las mismas credenciales)
$host = 'localhost';
$dbname = 'secretaria';
$username = 'root';
$password = ' ';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Obtener datos del POST
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validar datos
    if (empty($data['carrera']) || empty($data['cedula']) || !isset($data['notas'])) {
        echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
        exit;
    }
    
    $carrera = $data['carrera'];
    $cedula = $data['cedula'];
    $notas = $data['notas'];
    $definitiva = array_sum($notas) / count($notas);
    
    // Validar que la tabla exista
    $tablasPermitidas = ['ads', 'desastres', 'economia', 'enfermeria', 'ingenieria_civil'];
    if (!in_array($carrera, $tablasPermitidas)) {
        echo json_encode(['success' => false, 'error' => 'Carrera no válida']);
        exit;
    }
    
    // Aquí deberías crear una tabla para guardar las notas si no existe
    // Ejemplo de estructura:
    // CREATE TABLE notas (
    //     id INT AUTO_INCREMENT PRIMARY KEY,
    //     carrera VARCHAR(50) NOT NULL,
    //     cedula VARCHAR(20) NOT NULL,
    //     nota1 DECIMAL(4,2),
    //     nota2 DECIMAL(4,2),
    //     nota3 DECIMAL(4,2),
    //     nota4 DECIMAL(4,2),
    //     definitiva DECIMAL(4,2),
    //     fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    // );
    
    $stmt = $conn->prepare("INSERT INTO notas (carrera, cedula, nota1, nota2, nota3, nota4, definitiva) 
                           VALUES (:carrera, :cedula, :nota1, :nota2, :nota3, :nota4, :definitiva)");
    
    $stmt->bindParam(':carrera', $carrera);
    $stmt->bindParam(':cedula', $cedula);
    $stmt->bindParam(':nota1', $notas[0]);
    $stmt->bindParam(':nota2', $notas[1]);
    $stmt->bindParam(':nota3', $notas[2]);
    $stmt->bindParam(':nota4', $notas[3]);
    $stmt->bindParam(':definitiva', $definitiva);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'definitiva' => $definitiva]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al guardar las notas']);
    }
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Error de base de datos: ' . $e->getMessage()]);
}
?>