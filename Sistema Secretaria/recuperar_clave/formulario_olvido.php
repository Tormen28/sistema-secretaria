<?php
session_start();
require 'db.php'; // Archivo con la conexión a la base de datos
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/normalize.css">
    <title>Registrarse</title>
</head>
<body>
    
    <div class="caja__inicio__sesion">
      

        <div class="caja__inicio_sesion--formulario">

            <form action="verificar_usuario.php" method="POST">

            <div class="titulo">
            <h1>Recuperar contraseña</h1>
        </div>

                <div class="caja__formulario--fields">

                    <input type="text" name="usuario" id="usuario" placeholder="Nombre de usuario" required>
                   
                    <input type="email" name="correo" id="correo" placeholder="Correo electrónico" required>
                    


                </div>

                <div class="caja__formulario--botones">

                    <a href="../index.php">Regresar</a>
                    <button type="submit" name="submit">Validar</button> 
                    

                </div>

                


            </form>
            </div>

        

        


    </div>
    
</body>
</html>