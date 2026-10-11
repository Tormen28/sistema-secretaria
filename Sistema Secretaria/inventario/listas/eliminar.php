<?php
// eliminar.php

require_once 'conexion.php';

function eliminarRegistro($cedula, $tabla) {
    $tablasPermitidas = [
        'ads', 'enfermeria', 'ingenieria_civil', 
        'economia', 'desastres', 'sexto', 'tercero'
    ];
    
    if (!in_array($tabla, $tablasPermitidas)) {
        return false;
    }

    global $conn;
    
    $sql = "DELETE FROM `$tabla` WHERE cedula = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        error_log("Error en preparación: " . $conn->error);
        return false;
    }

    $stmt->bind_param('s', $cedula);
    
    if (!$stmt->execute()) {
        error_log("Error al eliminar: " . $stmt->error);
        return false;
    }

    $stmt->close();
    return true;
}

// Procesar solicitud
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['cedula'], $_GET['tabla'])) {
    $cedula = trim($_GET['cedula']);
    $tabla = htmlspecialchars($_GET['tabla']);
    
    // Validar y obtener página de origen
    $origen = 'index.php';
    if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) !== false) {
        $origen = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);
    }
    
    if (empty($cedula) || empty($tabla)) {
        header("Location: $origen?error=datos_invalidos");
        exit;
    }

    if (eliminarRegistro($cedula, $tabla)) {
        header("Location: $origen?eliminado=1");
    } else {
        header("Location: $origen?error=eliminacion");
    }
    exit;
    
} else {
    http_response_code(403);
    die('Acceso no autorizado');
}