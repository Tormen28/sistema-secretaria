<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $correo = $_POST['correo'];

    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE Nombre_Usuario = ? AND Correo_Electronico = ?");
    $stmt->bind_param("ss", $usuario, $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        header("Location: preguntas_seguridad.php");
        exit();
    } else {
        $_SESSION['error'] = "Datos incorrectos";
        header("Location: formulario_olvido.php");
        exit();
    }
}