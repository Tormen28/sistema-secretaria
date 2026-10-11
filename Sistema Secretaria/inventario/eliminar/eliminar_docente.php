<?php
    if (isset($_POST['cedula'])) {
    // Obtener la id de la solicitud de eliminación
    $cedula = $_POST['cedula'];

    // Conexión a la base de datos (reemplazar con tus propias credenciales)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "rrhh";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Consulta SQL para eliminar a la persona por su cedula
    $sql = "DELETE FROM docentes WHERE cedula = '$cedula'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>";
echo "window.onload = function() { 
    alert('EL PERSONAL ($cedula) ELIMINADO CORRECTAMENTE'); 
    window.location.href = 'lista_docentes.php';
};";
echo "</script>";
    } else {
        echo "<script>";
echo "window.onload = function() { 
    alert('ERROR AL ELIMINAR ($cedula)'); 
    window.location.href = 'lista_docentes.php';
};";
echo "</script>";
$conn->error;
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    echo "No se ha proporcionado un id para eliminar";
}
?>