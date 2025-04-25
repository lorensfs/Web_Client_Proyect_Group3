<?php
require_once '../models/usuariosModel.php';
header('Content-Type: application/json');

try {
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        $entity = $_GET['entity'] ?? 'user';
        if ($entity === 'role') {
            echo json_encode(Rol::getAll());
        } else {
            echo json_encode(Usuario::getAll());
        }
        exit;
    }

    $data = json_decode(file_get_contents("php://input"), true) ?? [];
    $entity = $data['entity'] ?? '';
    $action = $data['action'] ?? '';

    if ($entity === 'user') {

        if ($action === 'add') {
            $ok = Usuario::add(
                $data['nombre'],
                $data['correo'],
                $data['password'],
                (int) $data['rolId']
            );
            echo json_encode($ok);
        } elseif ($action === 'update') {
            $ok = Usuario::update(
                (int) $data['usuarioId'],
                $data['nombre'],
                $data['correo'],
                $data['password'] ?? null,
                (int) $data['rolId']
            );
            echo json_encode($ok);
        } elseif ($action === 'delete') {
            echo json_encode(Usuario::delete((int) $data['usuarioId']));
        } else {
            throw new Exception("Acción de usuario no válida");
        }

    } elseif ($entity === 'role') {

        if ($action === 'add') {
            echo json_encode(Rol::add($data['nombreRol'], $data['descripcion']));
        } elseif ($action === 'update') {
            echo json_encode(Rol::update(
                (int) $data['rolId'],
                $data['nombreRol'],
                $data['descripcion']
            ));
        } elseif ($action === 'delete') {
            echo json_encode(Rol::delete((int) $data['rolId']));
        } else {
            throw new Exception("Acción de rol no válida");
        }

    } else {
        throw new Exception("Entidad no reconocida.");
    }

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
