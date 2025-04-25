<?php
// Se incluye la configuración y conexión a la base de datos
require_once '../../config/database.php';

// Se establece que la respuesta será en formato JSON
header('Content-Type: application/json');

// Se verifica que la solicitud sea de tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Se obtienen los datos enviados desde el formulario
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $categoriaId = $_POST['categoriaId'] ?? '';
    $cantidad = $_POST['cantidad'] ?? 0;

    // Validación básica de los datos recibidos
    if (empty($nombre) || empty($descripcion) || empty($categoriaId) || !is_numeric($cantidad)) {
        // Si los datos son inválidos, se envía un mensaje de error en formato JSON y se termina la ejecución
        echo json_encode(['success' => false, 'error' => 'Datos incompletos o inválidos']);
        exit;
    }

    // Se convierte la cantidad y categoriaId a entero
    $cantidad = (int)$cantidad;
    $categoriaId = (int)$categoriaId;

    // Preparación de la consulta SQL para insertar un nuevo material
    $stmt = $conn->prepare("INSERT INTO material (nombre, descripcion, categoriaId, cantidad) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        // Si hay un error en la preparación de la consulta, se envía un mensaje de error
        echo json_encode(['success' => false, 'error' => 'Error en la preparación de la consulta']);
        exit;
    }

    // Se vinculan los parámetros a la consulta preparada
    $stmt->bind_param("ssii", $nombre, $descripcion, $categoriaId, $cantidad);

    // Se ejecuta la consulta y se verifica si fue exitosa
    if ($stmt->execute()) {
        // Si la inserción fue exitosa, se envía un mensaje de éxito
        echo json_encode(['success' => true, 'message' => 'Material registrado exitosamente']);
    } else {
        // Si hubo un error al insertar, se envía un mensaje de error
        echo json_encode(['success' => false, 'error' => 'Error al insertar en la base de datos']);
    }

    // Se cierra la consulta y la conexión a la base de datos
    $stmt->close();
    $conn->close();
} else {
    // Si el método HTTP no es POST, se envía un mensaje de error
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
