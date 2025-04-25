<?php
require_once '../models/usuariosModel.php';
header('Content-Type: application/json');

try {
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        echo json_encode(Usuario::getAll());
        exit;
    }

    $isJson = isset($_SERVER['CONTENT_TYPE'])
        && str_contains($_SERVER['CONTENT_TYPE'], 'application/json');

    if ($isJson) {
        $data = json_decode(file_get_contents("php://input"), true) ?? [];
    } else {
        $data = $_POST;
    }

    $entity = $data['entity'] ?? 'user';
    $action = $data['action'] ?? '';

    if ($entity !== 'user') {
        throw new Exception("Operación no permitida.");
    }

    switch ($action) {

        case 'add':
            $ok = Usuario::add($data['nombre'], $data['correo'], $data['password'], 2);
            echo json_encode(['ok' => $ok ? 1 : 0]);
            break;

        case 'update':
            $ok = Usuario::update(
                (int) $data['usuarioId'],
                $data['nombre'],
                $data['correo'],
                $data['password'] ?? null,
                (int) $data['rolId']
            );
            echo json_encode($ok);
            break;

        case 'delete':
            echo json_encode(Usuario::delete((int) $data['usuarioId']));
            break;

        default:
            throw new Exception("Acción de usuario no válida");
    }

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
