<?php
session_start();

function isLogged()
{
    return isset($_SESSION['usuarioId']);
}
function isAdmin()
{
    return isLogged() && $_SESSION['rolId'] == 1;
}
function isUsuario()
{
    return isLogged() && $_SESSION['rolId'] == 2;
}

if (!isLogged()) {
    header('Location: login.php');
    exit;
}
?>