<?php
    if (isset($_POST['id'])) {
    // Obtener la id de la solicitud de eliminación
    $id = $_POST['id'];

    // Conexión a la base de datos (reemplazar con tus propias credenciales)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "meca2";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Consulta SQL para eliminar el producto por su id
    $sql = "DELETE FROM productos WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>";
echo "window.onload = function() { 
    alert('ARTICULO ($id) ELIMINADO CORRECTAMENTE'); 
    window.location.href = 'productos_lista.php';
};";
echo "</script>";
    } else {
        echo "<script>";
echo "window.onload = function() { 
    alert('ERROR AL ELIMINAR ARTICULO ($id)'); 
    window.location.href = 'productos_lista.php';
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