<?php
session_start();
if (!isset($_SESSION['autorizado']) || !$_SESSION['autorizado']) {
    header("Location: formulario_olvido.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../css/normalize.css">
    <title>RRHH</title>
</head>
<body>

<div class="caja__inicio__sesion">
      

        <div class="caja__inicio_sesion--formulario">

        <form action="actualizar_contraseña.php" method="post">

        <div class="caja__formulario--fields">

    <label>Nueva contraseña:</label>
    <input type="password" name="nueva_contraseña" required>
    
    <label>Confirmar contraseña:</label>
    <input type="password" name="confirmar_contraseña" required>

    </div>

    <div class="caja__formulario--botones">
    
    <button type="submit">Cambiar contraseña</button>

    </div>
</form>

</div>

        


    </div>
</body>
</html>