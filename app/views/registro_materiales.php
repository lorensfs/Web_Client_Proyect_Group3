<?php require_once '../controllers/auth.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SMIR – Materiales y Categorías</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="../../public/css/styles.css" />
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
            <a href="../controllers/logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Salir</a>
        </div>

        <h1 class="mb-4">Registro de Materiales</h1>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h2 class="h5 mb-0">Gestión de Materiales</h2>
            </div>
            <div class="card-body">
                <button class="btn btn-primary mb-3" data-bs-toggle="modal"
                    data-bs-target="#agregarMaterialModal">Agregar Material</button>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="materialesTable">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Categoría</th>
                                <th>Cantidad</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="materialTablaBody"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mb-5">
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                <h2 class="h5 mb-0">Gestión de Categorías</h2>
            </div>
            <div class="card-body">
                <button class="btn btn-primary mb-3" data-bs-toggle="modal"
                    data-bs-target="#agregarCategoriaModal"> Nueva Categoría</button>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="categoriasTable">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="categoriasTablaBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="agregarMaterialModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="agregarMaterialForm" class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Agregar Material</h5><button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Nombre</label><input type="text" class="form-control"
                            id="materialNombre" required></div>
                    <div class="mb-3"><label class="form-label">Descripción</label><textarea class="form-control"
                            id="materialDescripcion" rows="3" required></textarea></div>
                    <div class="mb-3"><label class="form-label">Categoría</label><select class="form-select"
                            id="materialCategoria" required>
                            <option value="">Seleccione una categoría</option>
                        </select></div>
                    <div class="mb-3"><label class="form-label">Cantidad</label><input type="number"
                            class="form-control" id="materialCantidad" min="1" required></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Cancelar</button><button type="submit"
                        class="btn btn-primary">Guardar</button></div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editarMaterialModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="editarMaterialForm" class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Editar Material</h5><button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editarMaterialId">
                    <div class="mb-3"><label class="form-label">Nombre</label><input type="text" class="form-control"
                            id="editarMaterialNombre" required></div>
                    <div class="mb-3"><label class="form-label">Descripción</label><textarea class="form-control"
                            id="editarMaterialDescripcion" rows="3" required></textarea></div>
                    <div class="mb-3"><label class="form-label">Categoría</label><select class="form-select"
                            id="editarMaterialCategoria" required></select></div>
                    <div class="mb-3"><label class="form-label">Cantidad</label><input type="number"
                            class="form-control" id="editarMaterialCantidad" min="1" required></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Cancelar</button><button type="submit"
                        class="btn btn-warning">Guardar Cambios</button></div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="agregarCategoriaModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="agregarCategoriaForm" class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Agregar Categoría</h5><button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Nombre</label><input type="text" class="form-control"
                            id="categoriaNombre" required></div>
                    <div class="mb-3"><label class="form-label">Descripción</label><textarea class="form-control"
                            id="categoriaDescripcion" rows="3"></textarea></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Cancelar</button><button type="submit"
                        class="btn btn-primary">Guardar</button></div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editarCategoriaModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="editarCategoriaForm" class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Editar Categoría</h5><button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editarCategoriaId">
                    <div class="mb-3"><label class="form-label">Nombre</label><input type="text" class="form-control"
                            id="editarCategoriaNombre" required></div>
                    <div class="mb-3"><label class="form-label">Descripción</label><textarea class="form-control"
                            id="editarCategoriaDescripcion" rows="3"></textarea></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Cancelar</button><button type="submit"
                        class="btn btn-info">Guardar Cambios</button></div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net@1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            const tblMateriales = $('#materialesTable').DataTable({ language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' } })
            const tblCategorias = $('#categoriasTable').DataTable({ language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' } })

            function cargarCategoriasSelect() {
                $.getJSON('../controllers/CategoriasController.php', data => {
                    const selects = $('#materialCategoria, #editarMaterialCategoria')
                    selects.empty().append('<option value="">Seleccione una categoría</option>')
                    data.forEach(c => selects.append(`<option value="${c.categoriaId}">${c.nombre}</option>`))
                }).fail(() => alert('Error al cargar las categorías.'))
            }

            function cargarCategoriasTabla() {
                $.getJSON('../controllers/CategoriasController.php', data => {
                    tblCategorias.clear()
                    data.forEach(c => {
                        tblCategorias.row.add([
                            c.categoriaId,
                            c.nombre,
                            c.descripcion ?? '',
                            `<button class="btn btn-warning btn-sm mat-editar" data-id="${c.categoriaId}" data-nombre="${c.nombre}" data-descripcion="${c.descripcion ?? ''}">Editar</button>
                     <button class="btn btn-danger btn-sm mat-eliminar" data-id="${c.categoriaId}">Eliminar</button>`
                        ])
                    })
                    tblCategorias.draw()
                    cargarCategoriasSelect()
                }).fail(() => alert('Error al cargar las categorías.'))
            }

            $('#agregarCategoriaForm').submit(function (e) {
                e.preventDefault()
                const payload = { nombre: $('#categoriaNombre').val().trim(), descripcion: $('#categoriaDescripcion').val().trim() }
                if (!payload.nombre) return alert('El nombre es obligatorio.')
                $.post('../controllers/CategoriasController.php', payload, r => {
                    if (r.success) { $('#agregarCategoriaModal').modal('hide'); this.reset(); cargarCategoriasTabla(); alert(r.message) } else alert('Error: ' + r.error)
                }, 'json').fail(() => alert('Error de servidor al crear categoría.'))
            })

            $('#categoriasTablaBody').on('click', '.cat-editar', function () {
                const d = $(this).data()
                $('#editarCategoriaId').val(d.id)
                $('#editarCategoriaNombre').val(d.nombre)
                $('#editarCategoriaDescripcion').val(d.descripcion)
                $('#editarCategoriaModal').modal('show')
            })

            $('#editarCategoriaForm').submit(function (e) {
                e.preventDefault()
                const payload = { categoriaId: $('#editarCategoriaId').val(), nombre: $('#editarCategoriaNombre').val().trim(), descripcion: $('#editarCategoriaDescripcion').val().trim() }
                if (!payload.nombre) return alert('El nombre es obligatorio.')
                $.post('../controllers/CategoriasController.php', payload, r => {
                    if (r.success) { $('#editarCategoriaModal').modal('hide'); cargarCategoriasTabla(); alert(r.message) } else alert('Error: ' + r.error)
                }, 'json').fail(() => alert('Error de servidor al editar categoría.'))
            })

            $('#categoriasTablaBody').on('click', '.cat-eliminar', function () {
                const id = $(this).data('id')
                if (!confirm('¿Eliminar esta categoría?')) return
                $.post('../controllers/CategoriasController.php', { categoriaId: id }, r => {
                    if (r.success) { cargarCategoriasTabla(); alert(r.message) } else alert('Error: ' + r.error)
                }, 'json').fail(() => alert('Error de servidor al eliminar categoría.'))
            })

            function cargarMateriales() {
                $.getJSON('../controllers/InventarioController.php', data => {
                    tblMateriales.clear()
                    data.forEach(m => {
                        tblMateriales.row.add([
                            m.materialId,
                            m.nombre,
                            m.descripcion,
                            m.categoriaNombre ?? '',
                            m.cantidad,
                            `<button class="btn btn-warning btn-sm mat-editar" data-id="${m.materialId}" data-nombre="${m.nombre}" data-descripcion="${m.descripcion}" data-categoriaid="${m.categoriaId}" data-cantidad="${m.cantidad}">Editar</button>
                     <button class="btn btn-danger btn-sm mat-eliminar" data-id="${m.materialId}">Eliminar</button>`
                        ])
                    })
                    tblMateriales.draw()
                }).fail(() => alert('Error al cargar los materiales.'))
            }

            $('#agregarMaterialForm').submit(function (e) {
                e.preventDefault()
                const payload = {
                    nombre: $('#materialNombre').val().trim(),
                    descripcion: $('#materialDescripcion').val().trim(),
                    categoriaId: $('#materialCategoria').val(),
                    cantidad: parseInt($('#materialCantidad').val(), 10)
                }
                if (!payload.nombre || !payload.descripcion || !payload.categoriaId || payload.cantidad < 1) return alert('Complete todos los campos correctamente.')
                $.post('../controllers/RegistroMaterialesController.php', payload, r => {
                    if (r.success) { $('#agregarMaterialModal').modal('hide'); this.reset(); cargarMateriales(); alert(r.message) } else alert('Error: ' + r.error)
                }, 'json').fail(() => alert('Error de servidor al crear material.'))
            })

            $('#materialesTable tbody').on('click', '.mat-editar', function () {
                const d = $(this).data()
                $('#editarMaterialId').val(d.id)
                $('#editarMaterialNombre').val(d.nombre)
                $('#editarMaterialDescripcion').val(d.descripcion)
                $('#editarMaterialCategoria').val(d.categoriaid)
                $('#editarMaterialCantidad').val(d.cantidad)
                $('#editarMaterialModal').modal('show')
            })

            $('#editarMaterialForm').submit(function (e) {
                e.preventDefault()
                const payload = {
                    materialId: $('#editarMaterialId').val(),
                    nombre: $('#editarMaterialNombre').val().trim(),
                    descripcion: $('#editarMaterialDescripcion').val().trim(),
                    categoriaId: $('#editarMaterialCategoria').val(),
                    cantidad: parseInt($('#editarMaterialCantidad').val(), 10)
                }
                if (!payload.nombre || !payload.descripcion || !payload.categoriaId || payload.cantidad < 1) return alert('Complete todos los campos correctamente.')
                $.post('../controllers/EditarMaterialController.php', payload, r => {
                    if (r.success) { $('#editarMaterialModal').modal('hide'); cargarMateriales(); alert(r.message) } else alert('Error: ' + r.error)
                }, 'json').fail(() => alert('Error de servidor al editar material.'))
            })

            $('#materialesTable tbody').on('click', '.mat-eliminar', function () {
                const id = $(this).data('id')
                if (!confirm('¿Eliminar este material?')) return
                $.post('../controllers/EliminarMaterialController.php', { materialId: id }, r => {
                    if (r.success) { cargarMateriales(); alert(r.message) } else alert('Error: ' + r.error)
                }, 'json').fail(() => alert('Error de servidor al eliminar material.'))
            })

            cargarCategoriasTabla()
            cargarMateriales()
        })
    </script>

</body>

</html>