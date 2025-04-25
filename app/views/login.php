<?php
session_start();


$mostrarError = isset($_SESSION['login_error']);
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMIR - Registro</title>
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
                                <a onclick="location.href='./register.php'" value="Registrar"
                                    class="btn btn-outline-secondary mt-2">Ir a Registro</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>