<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: formulario_olvido.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT respuesta1, respuesta2, respuesta3 FROM preguntas_seguridad WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$respuestas = $result->fetch_assoc();

$correctas = true;
foreach (['respuesta1', 'respuesta2', 'respuesta3'] as $value) {
    $respuesta_usuario = $_POST[$value] ?? '';
    if ($respuesta_usuario !== $respuestas[$value]) {
        $correctas = false;
        break;
    }
}

if ($correctas) {
    $_SESSION['autorizado'] = true;
    header("Location: cambiar_contraseña.php");
} else {
    $_SESSION['error'] = "Respuestas incorrectas";
    header("Location: preguntas_seguridad.php");
}
exit();