<?php
if (isset($_POST['cedula'])) {
    // Obtener la cédula
    $cedula = trim($_POST['cedula']);
    
    // Validar que la cédula no esté vacía
    if (empty($cedula)) {
        die("<script>alert('Cédula no proporcionada'); window.location.href = 'lista_usuario.php';</script>");
    }

    // Conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "secretaria";  // Asegúrate de que este sea el nombre correcto de tu base de datos

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("<script>alert('Error de conexión: " . $conn->connect_error . "'); window.location.href = 'lista_usuario.php';</script>");
    }

    // Preparar la consulta SQL con sentencia preparada
    $sql = "DELETE FROM usuarios WHERE cedula = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("<script>alert('Error en la preparación de la consulta: " . $conn->error . "'); window.location.href = 'lista_usuario.php';</script>");
    }

    // Vincular parámetros y ejecutar
    $stmt->bind_param('s', $cedula);
    $result = $stmt->execute();

    if ($result) {
        echo "<script>
            alert('USUARIO CON CÉDULA: $cedula ELIMINADO CORRECTAMENTE');
            window.location.href = '../listas/lista_usuario.php';
        </script>";
    } else {
        echo "<script>
            alert('ERROR AL ELIMINAR EL USUARIO CON CÉDULA: $cedula\\nError: " . $stmt->error . "');
            window.location.href = '../listas/lista_usuario.php';
        </script>";
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('No se proporcionó una cédula para eliminar'); window.location.href = 'lista_usuario.php';</script>";
}
?>