<?php

    $conn = mysqli_connect("localhost", "root", "", "secretaria");
    function conectarDB() {
        $db = new mysqli('localhost', 'usuario', 'contraseña', 'basedatos');
        return $db;
    }
?>