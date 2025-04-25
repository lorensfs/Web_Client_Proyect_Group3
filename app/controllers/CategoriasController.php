<?php
// Controlador para obtener la lista de categorías

require_once '../../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $conn->query("SELECT categoriaId, nombre FROM categoria ORDER BY nombre ASC");

    $categorias = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }
    }

    echo json_encode($categorias);
} else {
    echo json_encode([]);
}
