<?php
session_start(); // Añadir si no existe
require 'login/funciones.php';

// Corregir sintaxis en el if
if (isset($_SESSION['id'])) { 
    header('Location: inventario/index.php');
    exit(); // Importante
}

$iniciosession = new inicio_sesion(); // Nombre de clase correcto

if (isset($_POST['submit'])) {
    $result = $iniciosession->inicioSession( // Nombre de método correcto
        $_POST['nombreUsuario__Correo'], 
        $_POST['contraseña']
    );

    if ($result == 1) {
        $_SESSION['iniciosession'] = true; 
        $_SESSION['id'] = $iniciosession->id; // Variable debe ser pública
        
        // Redirigir según el rol
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            header('Location: inventario/index.php');
        } else {
            header('Location: inventario/lista_solo_lectura.php');
        }
        exit();
    } else if ($result == 10) {
        echo "<script>alert('Contraseña Incorrecta');</script>";
    } else if ($result == 100) {
        echo "<script>alert('Usuario no registrado');</script>";
    }
}

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/normalize.css">
    <title>Iniciar Sesión</title>
    <script>
        // Prevenir que el usuario pueda volver atrás después de cerrar sesión
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            history.go(1);
        };
    </script>
</head>
<body>
    
    <div class="caja__inicio__sesion">
        

        <div class="caja__inicio_sesion--formulario">

            <form action="" method="post">

            <div class="titulo">
            <h1>Inicio de sesión</h1>
        </div>

                <div class="caja__formulario--fields">
                    <input type="text" name="nombreUsuario__Correo"  id="nombreUsuario__Correo" required placeholder="Nombre de usuario">
                    <input type="password" name="contraseña" id="contraseña" placeholder="Contraseña" required>

                </div>
                <div class="caja__formulario--botones">

                    <button type="submit" name="submit">Ingresar</button>
                    <a href="registro.php">Registrarse</a> <br> <br> <br>
                    <a href="recuperar_clave/formulario_olvido.php">Recuperar Clave</a>
                </div>


            </form>


        </div>

        


    </div>
    
</body>
</html>