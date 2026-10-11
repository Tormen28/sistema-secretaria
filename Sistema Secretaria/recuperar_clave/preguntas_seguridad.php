<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: formulario_olvido.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT pregunta1, pregunta2, pregunta3 FROM preguntas_seguridad WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$preguntas = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../css/normalize.css">
    <title>Secretaria</title>
</head>
<body>

<div class="caja__inicio__sesion">
      

        <div class="caja__inicio_sesion--formulario">

<form action="verificar_respuestas.php" method="post">

<div class="titulo">
            <h1>Ingrese sus preguntas de seguridad</h1>
        </div>

<div class="caja__formulario--fields">

    <label><?= $preguntas['pregunta1'] ?></label>
    <input type="text" name="respuesta1" required><br> <br>
    
    <label><?= $preguntas['pregunta2'] ?></label>
    <input type="text" name="respuesta2" required> <br> <br>

    <label><?= $preguntas['pregunta3'] ?></label>
    <input type="text" name="respuesta3" required> <br> <br>

    </div>

    
    <div class="caja__formulario--botones">
    
    <button type="submit">Verificar respuestas</button>

    </div>
    
    
</form>

</div>

        


    </div>
</body>
</html>