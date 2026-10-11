<?php

    require 'login/funciones.php';

    if (isset($_SESSION['id'])) {
        header('Location: home.php');
    }


    $registracion = new Registration();

    if (isset($_POST['submit'])) {
       $resultadoregistro = $registracion->registrarse(
        $_POST['nombrecompleto'],
        $_POST['nombreUsuario'],
        $_POST['cedula'],
        $_POST['correo'],
        $_POST['contraseña'],
        $_POST['confirmar_contraseña']
       );
       if ($resultadoregistro == 1) {
        echo '<script>
        alert("¡Registro exitoso!");
        window.location.href = "recuperar_clave/registrar.php";
    </script>';
       } elseif ($resultadoregistro == 10){
        echo "<script> alert('Registro ya existe'); </script>";

       } elseif ($resultadoregistro == 100){
        echo "<script> alert('Hubo un fallo'); </script>";
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/normalize.css">
    <title>Registrarse</title>
</head>
<body>
    
    <div class="caja__inicio__sesion">
      

        <div class="caja__inicio_sesion--formulario">

            <form action="" method="post">

            <div class="titulo">
            <h1>Registrarse</h1>
        </div>

                <div class="caja__formulario--fields">
                <input type="text" name="nombrecompleto" id="nombrecompleto" oninput="this.value = this.value.replace(/[^A-Za-z áéíóúÁÉÍÓÚñÑ]/g, '')" placeholder="Nombre completo" required>
                    <input type="text" name="nombreUsuario" id="nombreUsuario" placeholder="Nombre de usuario" required>
                   
                    <input type="tex" name="cedula" id="cedula" placeholder="cedula" required>
                    <script>
document.getElementById('cedula').addEventListener('focus', function(e) {
    // Agregar "V-" al enfocar si no existe
    if (!this.value.startsWith('V-')) {
        this.value = 'V-' + this.value.replace(/[^0-9]/g, '');
    }
});

document.getElementById('cedula').addEventListener('keydown', function(e) {
    const prefix = 'V-';
    const prefixLength = prefix.length;
    const selectionStart = this.selectionStart;
    
    // Bloquear edición antes del prefijo
    if (selectionStart < prefixLength) {
        if (![9, 16, 17, 18, 37, 38, 39, 40].includes(e.keyCode)) {
            e.preventDefault();
            return;
        }
    }

    // Permitir solo números y teclas de control después del prefijo
    if (selectionStart >= prefixLength) {
        const allowedKeys = [8, 9, 13, 37, 38, 39, 46]; // Teclas de control
        const isNumber = (e.keyCode >= 48 && e.keyCode <= 57) || 
                        (e.keyCode >= 96 && e.keyCode <= 105);
        
        if (!allowedKeys.includes(e.keyCode) && !isNumber) {
            e.preventDefault();
        }
    }
});

document.getElementById('cedula').addEventListener('input', function(e) {
    const prefix = 'V-';
    const prefixLength = prefix.length;
    let input = e.target;
    let caret = input.selectionStart;
    
    // Separar prefijo y números
    let numbers = input.value.slice(prefixLength).replace(/[^0-9]/g, '');
    
    // Formatear números con puntos
    let formatted = numbers.split('').reverse().join('')
                         .match(/.{1,3}/g)
                         ?.join('.')
                         .split('').reverse().join('') || '';
    
    // Reconstruir valor completo
    input.value = prefix + formatted;
    
    // Calcular nueva posición del cursor
    let numbersCaret = Math.max(caret - prefixLength, 0);
    let dotsAdded = Math.floor((numbers.length - 1) / 3);
    let newCaret = Math.min(
        prefixLength + numbersCaret + dotsAdded,
        input.value.length
    );
    
    input.setSelectionRange(newCaret, newCaret);
});
</script>

                    <input type="email" name="correo" id="correo" placeholder="Correo electrónico" required>
                    <input type="password" name="contraseña" id="contraseña" placeholder="Contraseña" required>
                    <input type="password" name="confirmar_contraseña" id="confirmar_contraseña" placeholder="Repetir la Contraseña" required>


                </div>
                <div class="caja__formulario--botones">

                    <a href="index.php">Regresar</a>
                    <button type="submit" name="submit">Registrarse</button>

                </div>


            </form>


        </div>

        


    </div>
    
</body>
</html>