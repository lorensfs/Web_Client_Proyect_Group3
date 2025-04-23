<?php
require_once '../../config/database.php';

$sql = "SELECT materialId, nombre, descripcion, cantidad FROM material WHERE deleted_at IS NULL";
$result = $conn->query($sql);

$materiales = [];

while ($row = $result->fetch_assoc()) {
    $materiales[] = $row;
}

echo json_encode($materiales);