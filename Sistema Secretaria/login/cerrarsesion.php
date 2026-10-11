<?php
// Iniciar la sesión antes de cualquier operación
session_start();

// Eliminar todas las variables de sesión
$_SESSION = array();

// Destruir la cookie de sesión si existe
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destruir la sesión
session_unset();
session_destroy();

// Prevenir el almacenamiento en caché de las páginas protegidas
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");

// Redirigir y asegurar que no haya más ejecución
header('Location: ../index.php?logout=1');
exit(); // Importante para detener la ejecución
?>