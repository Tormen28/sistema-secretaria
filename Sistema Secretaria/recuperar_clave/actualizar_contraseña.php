<?php
session_start();
require 'db.php';

if (!isset($_SESSION['autorizado']) || !$_SESSION['autorizado'] || !isset($_SESSION['user_id'])) {
    header("Location: formulario_olvido.php");
    exit();
}

if ($_POST['nueva_contraseña'] !== $_POST['confirmar_contraseña']) {
    $_SESSION['error'] = "Las contraseñas no coinciden";
    header("Location: cambiar_contraseña.php");
    exit();
}

$nueva_contraseña = password_hash($_POST['nueva_contraseña'], PASSWORD_DEFAULT);
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("UPDATE usuarios SET contraseña = ? WHERE id = ?");
$stmt->bind_param("si", $nueva_contraseña, $user_id);

if ($stmt->execute()) {
    session_destroy();
    $_SESSION['exito'] = "Contraseña actualizada correctamente";
    header("Location: ../index.php"); // Redirigir al login
} else {
    $_SESSION['error'] = "Error al actualizar la contraseña";
    header("Location: cambiar_contraseña.php");
}
exit();