<?php
error_reporting(0);
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "secretaria";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Conexión fallida']));
}
$definitiva = (float)$data['definitiva'];
if($definitiva > 20) {
    die(json_encode(['success' => false, 'message' => 'Nota excede el máximo permitido']));
}

$sql = "UPDATE notas SET 
        periodo = ?,
        definitiva = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sdi", 
    $data['periodo'],
    $definitiva,
    $data['id']
);

if($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $conn->error]);
}

$conn->close();
?>