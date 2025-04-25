<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SMIR | Registro de usuario</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="../../public/css/styles.css" />
</head>

<body class="bg-light">

    <div class="container login-container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card login-card shadow-sm border-0">
                    <div class="card-body p-4">

                        <h3 class="text-center mb-4">
                            <i class="fa-solid fa-user-plus me-2"></i>Registro
                        </h3>

                        <form id="frm-register" autocomplete="off">
                            <input type="hidden" name="entity" value="user" />
                            <input type="hidden" name="action" value="add" />

                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre completo</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" maxlength="100"
                                    required />
                            </div>

                            <div class="mb-3">
                                <label for="correo" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" id="correo" name="correo" maxlength="120"
                                    required />
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" minlength="6"
                                    required />
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Registrarse</button>
                                <a href="./login.php" class="btn btn-outline-secondary">Volver a inicio de sesión</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <script>
        document.getElementById('frm-register').addEventListener('submit', async (e) => {
            e.preventDefault();
            const data = Object.fromEntries(new FormData(e.target));

            try {
                const res = await fetch('../controllers/usuariosController.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data),
                });
                const json = await res.json();

                if (json.ok === 1 || json === 1) {
                    alert('Registro satisfactorio');
                    window.location.href = './login.php';
                } else {
                    alert(json.error || 'No se pudo completar el registro.');
                }
            } catch (err) {
                alert('Error de red o servidor: ' + err.message);
            }
        });
    </script>
</body>

</html>