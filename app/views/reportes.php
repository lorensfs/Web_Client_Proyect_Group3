<?php require_once '../controllers/auth.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMIR - Reportes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>

<body>

    <div class="sidebar">
        <h4 class="text-center">SMIR</h4>
        <?php if (isAdmin()): ?>
            <a class="nav-link text-white" href="usuarios.php"><i class="fas fa-users"></i> Usuarios </a>
            <a class="nav-link text-white" href="index.php"><i class="fas fa-home"></i> Inicio </a>
            <a class="nav-link text-white" href="registro_materiales.php"><i class="fas fa-box"></i> Registro de Materiales
            </a>
            <a class="nav-link text-white" href="inventario.php"><i class="fas fa-archive"></i> Inventario </a>
            <a class="nav-link text-white" href="reportes.php"><i class="fas fa-chart-bar"></i> Reportes </a>
        <?php elseif (isUsuario()): ?>
            <a class="nav-link text-white" href="index.php"><i class="fas fa-home"></i> Inicio </a>
            <a class="nav-link text-white" href="registro_materiales.php"><i class="fas fa-box"></i> Registro de Materiales
            </a>
            <a class="nav-link text-white" href="inventario.php"><i class="fas fa-archive"></i> Inventario </a>
            <a class="nav-link text-white" href="reportes.php"><i class="fas fa-chart-bar"></i> Reportes </a>
        <?php endif; ?>
    </div>

    <div class="content my-4">
        <div class="d-flex justify-content-end mb-3">
            <a href="../controllers/logout.php" class="btn btn-danger">
                <i class="fas fa-sign-out-alt"></i> Salir
            </a>
        </div>

        <h1 class="mb-4">Reportes</h1>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h2 class="h5 mb-0">Generación de Informes</h2>
            </div>
            <div class="card-body">
                <form>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="fechaInicio" class="form-label">Fecha Inicio</label>
                            <input type="date" class="form-control" id="fechaInicio">
                        </div>
                        <div class="col-md-4">
                            <label for="fechaFin" class="form-label">Fecha Fin</label>
                            <input type="date" class="form-control" id="fechaFin">
                        </div>
                        <div class="col-md-4">
                            <label for="tipoInforme" class="form-label">Tipo de Informe</label>
                            <select class="form-control" id="tipoInforme">
                                <option value="inventario">Inventario</option>
                                <option value="usuarios">Usuarios</option>
                            </select>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary mt-3" id="generarBtn">Generar Informe</button>
                </form>
            </div>
        </div>

        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-success me-2" id="exportExcel"><i class="fas fa-file-excel"></i> Exportar a
                Excel</button>
            <button class="btn btn-danger" id="exportPDF"><i class="fas fa-file-pdf"></i> Exportar a PDF</button>
        </div>

        <div id="resultadoReporte" class="mt-4"></div>
    </div>

    <!-- Librerías -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

    <!-- Lógica -->
    <script>
        $(document).ready(function () {

            $('#tipoInforme').on('change', function () {
                const tipo = $(this).val();
                const activar = tipo === 'ventas';

                $('#fechaInicio').prop('disabled', !activar);
                $('#fechaFin').prop('disabled', !activar);
            }).trigger('change');

            $('#generarBtn').on('click', function () {
                const tipo = $('#tipoInforme').val();
                const fechaInicio = $('#fechaInicio').val();
                const fechaFin = $('#fechaFin').val();

                $.post('../controllers/reporte_data.php', {
                    tipo: tipo,
                    fechaInicio: fechaInicio,
                    fechaFin: fechaFin
                }, function (data) {
                    let html = '';

                    if (!data || data.length === 0) {
                        html = '<p class="text-center">No se encontraron resultados.</p>';
                    } else {
                        html += '<div class="card">';
                        html += '<div class="card-header bg-primary text-white">';
                        html += '<h5 class="mb-0">Resultado del Informe</h5>';
                        html += '</div>';
                        html += '<div class="card-body">';
                        html += '<table class="table table-bordered" id="tablaReporte"><thead><tr>';
                        for (let key in data[0]) {
                            html += `<th>${key}</th>`;
                        }
                        html += '</tr></thead><tbody>';

                        data.forEach(row => {
                            html += '<tr>';
                            for (let key in row) {
                                html += `<td>${row[key]}</td>`;
                            }
                            html += '</tr>';
                        });

                        html += '</tbody></table>';
                        html += '</div></div>';
                    }

                    $('#resultadoReporte').html(html);
                }, 'json');
            });

            $('#exportExcel').on('click', function () {
                const table = document.getElementById("tablaReporte");
                if (!table) return alert("Primero genera un informe.");

                const wb = XLSX.utils.table_to_book(table, { sheet: "Reporte" });
                XLSX.writeFile(wb, "Reporte_SMIR.xlsx");
            });

            $('#exportPDF').on('click', function () {
                const table = document.getElementById("tablaReporte");
                if (!table) return alert("Primero genera un informe.");

                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                doc.autoTable({ html: '#tablaReporte', startY: 20 });
                doc.text("Reporte SMIR", 14, 15);
                doc.save("Reporte_SMIR.pdf");
            });
        });
    </script>

</body>

</html>