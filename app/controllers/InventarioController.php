<?php
require_once '../../config/database.php';

$sql = "SELECT m.materialId, m.nombre, m.descripcion, m.cantidad, c.nombre AS categoriaNombre 
        FROM material m 
        LEFT JOIN categoria c ON m.categoriaId = c.categoriaId 
        WHERE m.deleted_at IS NULL";
$result = $conn->query($sql);

$materiales = [];

while ($row = $result->fetch_assoc()) {
    $materiales[] = $row;
}

echo json_encode($materiales);
