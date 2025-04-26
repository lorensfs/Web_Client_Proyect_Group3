<?php
require_once '../../config/database.php';
require_once '../models/usuariosModel.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function ensureRole(mysqli $conn, string $nombreRol, string $descripcion = ''): int
{
    $stmt = $conn->prepare(
        "SELECT rolId FROM rol
         WHERE nombreRol = ? AND deleted_at IS NULL
         LIMIT 1"
    );
    $stmt->bind_param("s", $nombreRol);
    $stmt->execute();
    $stmt->bind_result($id);
    if ($stmt->fetch()) {
        return $id;
    }

    $stmt = $conn->prepare(
        "INSERT INTO rol (nombreRol, descripcion) VALUES (?, ?)"
    );
    $stmt->bind_param("ss", $nombreRol, $descripcion);
    $stmt->execute();

    return $stmt->insert_id;
}

function ensureAdminUser(mysqli $conn, int $adminRoleId): void
{
    $correo = 'admin@gmail.com';
    $nombre = 'Admin';
    $plain = 'Admin';

    $stmt = $conn->prepare(
        "SELECT usuarioId FROM usuario
         WHERE LOWER(correo) = LOWER(?) AND deleted_at IS NULL
         LIMIT 1"
    );
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    if ($stmt->fetch()) {
        return;
    }

    Usuario::add($nombre, $correo, $plain, $adminRoleId, );
}

$conn->begin_transaction();
try {
    $adminRoleId = ensureRole($conn, 'Admin', 'Usuario con privilegios administrativos');
    ensureRole($conn, 'User', 'Usuario estándar');
    ensureAdminUser($conn, $adminRoleId);
    $conn->commit();
} catch (Throwable $e) {
    $conn->rollback();
    throw $e;
}

session_start();


$mostrarError = isset($_SESSION['login_error']);
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMIR - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>

<body>

    <div class="container login-container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card login-card shadow">
                    <div class="card-body">
                        <h3 class="text-center mb-4">SMIR - Iniciar Sesión</h3>

                        <?php if ($mostrarError): ?>
                            <p class="text-danger text-center">Correo o contraseña incorrectos</p>
                        <?php endif; ?>

                        <form action="..\controllers\login.php" method="POST">
                            <div class="mb-3">
                                <label for="correo" class="form-label">Correo</label>
                                <input type="email" class="form-control" id="correo" name="correo"
                                    placeholder="Ingresa tu correo" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Ingresa tu contraseña" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Ingresar</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>