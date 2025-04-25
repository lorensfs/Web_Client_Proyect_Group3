<?php
require_once(__DIR__ . '/../../config/database.php');
header('Content-Type: application/json');

$tipo = $_POST['tipo'] ?? '';
$fechaInicio = $_POST['fechaInicio'] ?? null;
$fechaFin = $_POST['fechaFin'] ?? null;

$resultado = [];

switch ($tipo) {
    case 'ventas':
        $sql = "SELECT s.salidaId, m.nombre AS material, s.cantidad, s.fechaSalida
                FROM salida s
                INNER JOIN material m ON s.materialId = m.materialId
                WHERE s.deleted_at IS NULL";
        if ($fechaInicio && $fechaFin) {
            $sql .= " AND DATE(s.fechaSalida) BETWEEN ? AND ?";
        }

        $stmt = $conn->prepare($sql);
        if ($fechaInicio && $fechaFin) {
            $stmt->bind_param("ss", $fechaInicio, $fechaFin);
        }
        break;

    case 'inventario':
        $sql = "SELECT m.materialId, m.nombre, m.descripcion, m.cantidad, c.nombre AS categoria
                FROM material m
                LEFT JOIN categoria c ON m.categoriaId = c.categoriaId
                WHERE m.deleted_at IS NULL";
        $stmt = $conn->prepare($sql);
        break;

    case 'usuarios':
        $sql = "SELECT u.usuarioId, u.nombre, u.correo, r.nombreRol
                FROM usuario u
                LEFT JOIN rol r ON u.rolId = r.rolId
                WHERE u.deleted_at IS NULL";
        $stmt = $conn->prepare($sql);
        break;

    default:
        echo json_encode(['error' => 'Tipo de reporte inválido']);
        exit;
}

$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $resultado[] = $row;
}

echo json_encode($resultado);
?>