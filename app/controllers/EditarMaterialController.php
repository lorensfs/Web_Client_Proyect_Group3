<?php
// Controlador para editar un material en la base de datos

require_once '../../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $materialId = $_POST['materialId'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $categoriaId = $_POST['categoriaId'] ?? '';
    $cantidad = $_POST['cantidad'] ?? 0;

    if (empty($materialId) || !is_numeric($materialId) || empty($nombre) || empty($descripcion) || empty($categoriaId) || !is_numeric($cantidad)) {
        echo json_encode(['success' => false, 'error' => 'Datos incompletos o inválidos']);
        exit;
    }

    $materialId = (int)$materialId;
    $cantidad = (int)$cantidad;
    $categoriaId = (int)$categoriaId;

    $stmt = $conn->prepare("UPDATE material SET nombre = ?, descripcion = ?, categoriaId = ?, cantidad = ? WHERE materialId = ?");
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => 'Error en la preparación de la consulta']);
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
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
