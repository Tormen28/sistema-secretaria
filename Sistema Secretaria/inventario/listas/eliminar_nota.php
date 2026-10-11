<?php
error_reporting(0);
header('Content-Type: application/json');

$id = $_GET['id'];

$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "secretaria";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Conexión fallida']));
}

$sql = "DELETE FROM notas WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $conn->error]);
}

$conn->close();
?>