<?php
require_once(__DIR__ . '/../../config/database.php');
header('Content-Type: application/json');

$response = [];

// Total de productos y usuarios
$sqlProductos = "SELECT COUNT(*) AS total FROM material WHERE deleted_at IS NULL";
$res = $conn->query($sqlProductos);
$response['totalProductos'] = $res->fetch_assoc()['total'];


$sqlUsuarios = "SELECT COUNT(*) AS total FROM usuario WHERE deleted_at IS NULL";
$res = $conn->query($sqlUsuarios);
$response['totalUsuarios'] = $res->fetch_assoc()['total'];

// Inventario por categoría
$sqlCategorias = "
    SELECT c.nombre AS nombre, SUM(m.cantidad) AS total
    FROM material m
    JOIN categoria c ON m.categoriaId = c.categoriaId
    WHERE m.deleted_at IS NULL
    GROUP BY c.nombre
";
$res = $conn->query($sqlCategorias);
$response['categorias'] = [];
while ($row = $res->fetch_assoc()) {
    $response['categorias'][] = $row;
}

// Actividad reciente 
$sqlActividad = "
    SELECT 'Salida' AS tipo, m.nombre AS material, s.cantidad, s.fechaSalida AS fecha
    FROM salida s
    JOIN material m ON s.materialId = m.materialId
    WHERE s.deleted_at IS NULL
    UNION ALL
    SELECT 'Entrada' AS tipo, 'Entrada de materiales' AS material, e.cantidad, e.fechaEntrada AS fecha
    FROM entrada e
    WHERE e.deleted_at IS NULL
    ORDER BY fecha DESC
    LIMIT 5
";
$res = $conn->query($sqlActividad);
$response['actividad'] = [];
while ($row = $res->fetch_assoc()) {
    $response['actividad'][] = $row;
}

echo json_encode($response);
?>
