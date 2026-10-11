<?php

    require '../login/funciones.php';

    if (isset($_SESSION['id'])) {
        header('Location: home.php');
    }


    $registracion = new Registration();

    if (isset($_POST['submit'])) {
       $resultadoregistro = $registracion->preguntas(
        $_POST['pregunta1'],
        $_POST['respuesta1'],
        $_POST['pregunta2'],
        $_POST['respuesta2'],
        $_POST['pregunta3'],
        $_POST['respuesta3']
       );
       if ($resultadoregistro == 1) {
        echo '<script>
        alert("¡Registro exitoso!");
        window.location.href = "../index.php";
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
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../css/normalize.css">
    <title>Registrarse</title>
</head>
<body>
    
    <div class="caja__inicio__sesion">
      

        <div class="caja__inicio_sesion--formulario">

        <form action=" " method="POST">
    <div class="titulo">
        <h1>Preguntas de Seguridad</h1>
    </div>

    

        <!-- Pregunta 1 -->
        <div class="security-question">
            <label>Selecciona tu primera pregunta:</label> 
            <select name="pregunta1" required> <br>
                <option value="¿Cómo se llamaba tu primera mascota?">¿Cómo se llamaba tu primera mascota?</option>
                <option value="¿Cuál es el nombre de tu ciudad natal?">¿Cuál es el nombre de tu ciudad natal?</option>
                <option value="¿Cuál era tu comida favorita en la infancia?">¿Cuál era tu comida favorita en la infancia?</option>
            </select> <br> <br>
            <label>Respuesta:</label> <br> 
            <input type="text" name="respuesta1" placeholder="Respuesta" required>
        </div>

        <!-- Pregunta 2 -->
        <div class="security-question">
            <label>Selecciona tu segunda pregunta:</label> 
            <select name="pregunta2" required> <br>
                <option value="¿Cuál fue tu primer trabajo?">¿Cuál fue tu primer trabajo?</option>
                <option value="¿Cómo se llama tu mejor amigo de la infancia?">¿Cómo se llama tu mejor amigo de la infancia?</option>
                <option value="¿Cuál es el nombre de tu profesor favorito?">¿Cuál es el nombre de tu profesor favorito?</option>
            </select> <br> <br>
            <label>Respuesta:</label> <br>
            <input type="text" name="respuesta2" placeholder="Respuesta" required>
        </div>


     <!-- Pregunta 3 -->
     <div class="security-question">
            <label>Selecciona tu tercera pregunta:</label> <br>
            <select name="pregunta3" required> <br>
                <option value="¿Cuál es tu Super héroe favorito?">¿Cuál es tu Super héroe favorito?</option>
                <option value="¿Cuál es tu país favorito?">¿Cuál es tu país favorito?</option>
                <option value="¿Cuál es tu color favorito?">¿Cuál es tu color favorito?</option>
            </select> <br> <br>
            <label>Respuesta:</label> <br>
            <input type="text" name="respuesta3" placeholder="Respuesta" required>
        </div>
    </div>
 
    <!-- Botones -->
    <div class="caja_formulario-botones">
    
    <button type="submit" name="submit" class="btn btn-primary">Guardar preguntas</button>
    </div>
</form>


        </div>

        


    </div>
    
</body>
</html>