<?php
require_once '../../config/database.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $sql = "SELECT categoriaId, nombre, descripcion 
            FROM categoria 
            WHERE deleted_at IS NULL 
            ORDER BY nombre ASC";
    $result = $conn->query($sql);
    echo json_encode($result ? $result->fetch_all(MYSQLI_ASSOC) : []);
    exit;
}

if ($method === 'POST') {

    /*  ───────────── ALTA ───────────── */
    if (!isset($_POST['categoriaId']) && isset($_POST['nombre'])) {
        $nombre = trim($_POST['nombre']);
        $descripcion = trim($_POST['descripcion'] ?? '');
        if ($nombre === '') {
            echo json_encode(['success' => false, 'error' => 'Nombre requerido']);
            exit;
        }
        $stmt = $conn->prepare("INSERT INTO categoria (nombre, descripcion) VALUES (?, ?)");
        $stmt->bind_param("ss", $nombre, $descripcion);
        echo json_encode($stmt->execute()
            ? ['success' => true, 'message' => 'Categoría creada']
            : ['success' => false, 'error' => 'Error al crear']);
        exit;
    }

    /*  ───────────── EDICIÓN ───────────── */
    if (isset($_POST['categoriaId']) && isset($_POST['nombre'])) {
        $id = $_POST['categoriaId'];
        $nombre = trim($_POST['nombre']);
        $descripcion = trim($_POST['descripcion'] ?? '');
        if (!is_numeric($id) || $nombre === '') {
            echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
            exit;
        }
        $id = (int) $id;
        $stmt = $conn->prepare(
            "UPDATE categoria SET nombre = ?, descripcion = ? 
             WHERE categoriaId = ? AND deleted_at IS NULL"
        );
        $stmt->bind_param("ssi", $nombre, $descripcion, $id);
        echo json_encode($stmt->execute()
            ? ['success' => true, 'message' => 'Categoría actualizada']
            : ['success' => false, 'error' => 'Error al actualizar']);
        exit;
    }

    /*  ───────────── ELIMINACIÓN ───────────── */
    if (isset($_POST['categoriaId']) && !isset($_POST['nombre'])) {
        $id = $_POST['categoriaId'];
        if (!is_numeric($id)) {
            echo json_encode(['success' => false, 'error' => 'ID inválido']);
            exit;
        }
        $id = (int) $id;
        $stmt = $conn->prepare(
            "UPDATE categoria SET deleted_at = NOW() 
             WHERE categoriaId = ? AND deleted_at IS NULL"
        );
        $stmt->bind_param("i", $id);
        echo json_encode($stmt->execute() && $stmt->affected_rows
            ? ['success' => true, 'message' => 'Categoría eliminada']
            : ['success' => false, 'error' => 'No encontrada o ya eliminada']);
        exit;
    }
}

echo json_encode(['success' => false, 'error' => 'Operación no válida']);
