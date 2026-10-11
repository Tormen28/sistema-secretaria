<?php
error_reporting(0);
$cedula = $_GET['cedula'];

$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "secretaria";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT * FROM notas WHERE cedula = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $cedula);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<h3>Notas del Estudiante: $cedula</h3>";
    echo "<table>";
    echo "<tr>
        <th>Periodo</th>
        <th>Materia</th>
        <th>Código</th>
        <th>Definitiva</th>
        <th>Acciones</th>
      </tr>";
    
    while($row = $result->fetch_assoc()) {
       // Dentro del while loop
echo "<tr data-id='".$row['id']."'>
<td>
    <span class='view-mode'>".$row['periodo']."</span>
    <input type='text' name='periodo' value='".$row['periodo']."' 
           class='edit-mode' style='display:none' maxlength='20'>
</td>
<td>".$row['nombre_materia']."</td>
<td>".$row['codigo']."</td>
<td>
    <span class='view-mode'>".$row['definitiva']."</span>
    <input type='number' step='0.01' name='definitiva' 
           value='".$row['definitiva']."' 
           class='edit-mode' style='display:none' min='0' max='20'>
</td>
<td>
    <button class='btn-editar' onclick='habilitarEdicion(this)'>Editar</button>
    <button class='btn-guardar' onclick='guardarCambios(this)' style='display:none'>Guardar</button>
    <button class='btn-cancelar' onclick='cancelarEdicion(this)' style='display:none'>Cancelar</button>
    <button class='btn-eliminar' onclick='eliminarNota(".$row['id'].")'>Eliminar</button>
</td>
</tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron notas para esta cédula";
}

$conn->close();
?>