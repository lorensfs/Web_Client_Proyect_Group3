<?php
require_once(__DIR__ . '/../../config/database.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuario WHERE correo = ? AND password = ? AND deleted_at IS NULL";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $correo, $password);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        $_SESSION['usuarioId'] = $usuario['usuarioId'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rolId'] = $usuario['rolId'];

        header("Location: ../../index.html");
        exit;
    } else {
        $_SESSION['login_error'] = true;
        header("Location: ../../login.php"); 
    }
}
?>
