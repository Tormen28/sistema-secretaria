<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['iniciosession'])) {
    header('Location: ../index.php');
    exit();
}

// Verificar si el usuario es administrador
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    // Si no es administrador, redirigir a la vista de solo lectura
    header('Location: lista_solo_lectura.php');
    exit();
}
?>
