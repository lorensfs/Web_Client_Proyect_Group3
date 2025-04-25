<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../../config/database.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $materialNombre = $_POST['nombre'] ?? '';
    $cantidad = intval($_POST['cantidad'] ?? 0);

    if (!$materialNombre || $cantidad <= 0) {
        echo json_encode(["success" => false, "error" => "Datos inválidos"]);
        exit;
    }

    $stmt = $conn->prepare("SELECT materialId FROM material WHERE nombre = ? AND deleted_at IS NULL");
    $stmt->bind_param("s", $materialNombre);
    $stmt->execute();
    $stmt->bind_result($materialId);
    $stmt->fetch();
    if (!$materialId) {
        echo json_encode(["success"=>false,"error"=>"Material NO encontrado"]);
        exit;
    }
    $stmt->close();

    if ($materialId) {
        $insert = $conn->prepare("INSERT INTO pedido (materialId, cantidad) VALUES (?, ?)");
        $insert->bind_param("ii", $materialId, $cantidad);
        if ($insert->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => "Error al insertar pedido"]);
        }
        $insert->close();
    } else {
        echo json_encode(["success" => false, "error" => "Material no encontrado"]);
    }

    $conn->close();
}