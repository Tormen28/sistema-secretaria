<?php
session_start();

// Verificar si el usuario no ha iniciado sesión
if (!isset($_SESSION['id'])) {
    // Destruir cualquier sesión existente
    session_unset();
    session_destroy();
    
    // Redirigir a la página de inicio de sesión
    header("Location: ../index.php?session_expired=1");
    exit();
}

// Prevenir almacenamiento en caché de las páginas protegidas
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");
?>
