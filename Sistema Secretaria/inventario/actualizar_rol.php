<?php
// Iniciar el buffer de salida
ob_start();

// Configuración de errores (solo para desarrollo)
error_reporting(E_ALL);
ini_set('display_errors', 0);  // Desactivar la visualización de errores en producción
ini_set('log_errors', 1);      // Registrar errores en el archivo de log

// Iniciar sesión
session_start();

// Función para enviar respuesta JSON y terminar la ejecución
function sendJsonResponse($success, $message = '', $data = null) {
    // Limpiar cualquier salida anterior
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    // Establecer la cabecera JSON
    header('Content-Type: application/json');
    
    // Crear y enviar la respuesta
    $response = ['success' => $success];
    if ($message !== '') $response['message'] = $message;
    if ($data !== null) $response['data'] = $data;
    
    echo json_encode($response);
    exit;
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['iniciosession']) || $_SESSION['iniciosession'] !== true) {
    sendJsonResponse(false, 'No autorizado. Por favor inicie sesión.');
}

// Verificar si es una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Método no permitido');
}

// Verificar que se hayan recibido los datos necesarios
if (!isset($_POST['user_id']) || !isset($_POST['new_role'])) {
    sendJsonResponse(false, 'Datos incompletos');
}

// Obtener y validar los datos
$user_id = filter_var($_POST['user_id'], FILTER_VALIDATE_INT);
$new_role = filter_var($_POST['new_role'], FILTER_SANITIZE_STRING);

if (!$user_id || !in_array($new_role, ['admin', 'viewer'])) {
    sendJsonResponse(false, 'Datos inválidos');
}

// Conectar a la base de datos
try {
    $servername = "localhost:3306";
    $username = "root";
    $password = "";
    $dbname = "secretaria";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception('Error de conexión a la base de datos: ' . $conn->connect_error);
    }

    // Actualizar el rol del usuario
    $stmt = $conn->prepare("UPDATE usuarios SET rol = ? WHERE id = ?");
    if ($stmt === false) {
        throw new Exception('Error al preparar la consulta: ' . $conn->error);
    }

    $stmt->bind_param("si", $new_role, $user_id);
    
    if (!$stmt->execute()) {
        throw new Exception('Error al ejecutar la consulta: ' . $stmt->error);
    }

    if ($stmt->affected_rows === 0) {
        sendJsonResponse(false, 'No se encontró el usuario o no se realizaron cambios');
    }

    $stmt->close();
    $conn->close();
    
    // Éxito
    sendJsonResponse(true, '¡Éxito!');
    
} catch (Exception $e) {
    // Limpiar cualquier salida anterior
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    // En producción, no mostrar detalles del error al usuario
    error_log('Error en actualizar_rol.php: ' . $e->getMessage());
    sendJsonResponse(false, 'Ocurrió un error al procesar la solicitud');
}
?>
