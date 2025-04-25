<?php
// Controlador para eliminar un material de la base de datos

require_once '../../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $materialId = $_POST['materialId'] ?? '';

    if (empty($materialId) || !is_numeric($materialId)) {
        echo json_encode(['success' => false, 'error' => 'ID de material inválido']);
        exit;
    }

    $materialId = (int)$materialId;

    // Se puede usar borrado lógico o físico, aquí se hace borrado físico
    $stmt = $conn->prepare("DELETE FROM material WHERE materialId = ?");
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => 'Error en la preparación de la consulta']);
        exit;
    }

    $stmt->bind_param("i", $materialId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Material eliminado exitosamente']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al eliminar el material']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
