<?php
require_once '../../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit;
}

$materialId = $_POST['materialId'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$categoriaId = $_POST['categoriaId'] ?? '';
$cantidad = $_POST['cantidad'] ?? '';

if (
    !is_numeric($materialId) || !is_numeric($categoriaId) || !is_numeric($cantidad) ||
    !$nombre || !$descripcion || $cantidad < 1
) {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos o inválidos']);
    exit;
}

$materialId = (int) $materialId;
$categoriaId = (int) $categoriaId;
$cantidad = (int) $cantidad;

$stmt = $conn->prepare("SELECT 1 FROM categoria WHERE categoriaId = ? AND deleted_at IS NULL");
$stmt->bind_param("i", $categoriaId);
$stmt->execute();
if (!$stmt->get_result()->num_rows) {
    echo json_encode(['success' => false, 'error' => 'Categoría no válida o eliminada']);
    exit;
}
$stmt->close();

$stmt = $conn->prepare(
    "UPDATE material
     SET nombre = ?, descripcion = ?, categoriaId = ?, cantidad = ?
     WHERE materialId = ? AND deleted_at IS NULL"
);
if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'Error al preparar la consulta']);
    exit;
}
$stmt->bind_param("ssiii", $nombre, $descripcion, $categoriaId, $cantidad, $materialId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Material actualizado exitosamente']);
} else {
    echo json_encode(['success' => false, 'error' => 'Error al actualizar el material']);
}
$stmt->close();
$conn->close();
