<?php
require_once '../../config/database.php';
header('Content-Type: application/json');

$sql = "SELECT
            m.materialId,
            m.nombre,
            m.descripcion,
            m.cantidad,
            m.categoriaId,              
            c.nombre AS categoriaNombre
        FROM   material  m
        LEFT   JOIN categoria c ON c.categoriaId = m.categoriaId AND c.deleted_at IS NULL
        WHERE  m.deleted_at IS NULL";

$result = $conn->query($sql);
$materiales = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

echo json_encode($materiales);
