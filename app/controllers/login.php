<?php
require_once __DIR__ . '/../../config/database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'] ?? '';
    $password = $_POST['password'] ?? '';


    $sql = "SELECT usuarioId, nombre, rolId, password FROM usuario 
             WHERE correo = ? AND deleted_at IS NULL";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $correo);
    $stmt->execute();
    $res = $stmt->get_result();


    if ($res->num_rows === 1) {
        $usuario = $res->fetch_assoc();

        if (password_verify($password, $usuario['password'])) {

            $_SESSION['usuarioId'] = $usuario['usuarioId'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rolId'] = $usuario['rolId'];

            header('Location: ../views/index.php');
            exit;
        }
    }


    $_SESSION['login_error'] = true;
    header('Location: ../views/login.php');
    exit;
}
?>