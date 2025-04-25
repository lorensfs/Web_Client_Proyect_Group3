<?php
require_once '../../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit;
}

$materialId = $_POST['materialId'] ?? '';
if (!is_numeric($materialId)) {
    echo json_encode(['success' => false, 'error' => 'ID de material inválido']);
    exit;
}
$materialId = (int) $materialId;

$stmt = $conn->prepare("UPDATE material SET deleted_at = NOW() WHERE materialId = ? AND deleted_at IS NULL");
if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'Error al preparar la consulta']);
    exit;
}
$stmt->bind_param("i", $materialId);

if ($stmt->execute() && $stmt->affected_rows) {
    echo json_encode(['success' => true, 'message' => 'Material eliminado exitosamente']);
} else {
    echo json_encode(['success' => false, 'error' => 'No se encontró el material o ya estaba eliminado']);
}
$stmt->close();
$conn->close();
