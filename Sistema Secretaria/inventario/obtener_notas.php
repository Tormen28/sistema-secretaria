<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si se recibió la cédula
if (!isset($_POST['cedula'])) {
    echo json_encode(['success' => false, 'message' => 'No se proporcionó la cédula del estudiante']);
    exit;
}

// Incluir archivo de conexión
require_once('./listas/conexion.php');

// Verificar si hay error de conexión
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
    exit;
}

// Obtener y limpiar la cédula
$cedula = trim($_POST['cedula']);

// Preparar la consulta para obtener las notas del estudiante
$query = "SELECT nombre_materia, definitiva, periodo, tipo_materia 
          FROM notas 
          WHERE cedula = ? 
          ORDER BY periodo DESC, nombre_materia ASC";

$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, 's', $cedula);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $notas = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $notas[] = [
            'nombre_materia' => $row['nombre_materia'],
            'definitiva' => $row['definitiva'],
            'periodo' => $row['periodo'],
            'tipo_materia' => $row['tipo_materia']
        ];
    }
    
    mysqli_stmt_close($stmt);
    
    // Devolver las notas como JSON
    echo json_encode([
        'success' => true,
        'notas' => $notas
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error al preparar la consulta: ' . mysqli_error($conn)
    ]);
}

// Cerrar la conexión
mysqli_close($conn);
?>
