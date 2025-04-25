const api = '../controllers/usuariosController.php';
let dtUsuarios, dtRoles;

function listarRoles() {
    fetch(`${api}?entity=role`)
        .then(r => r.json())
        .then(data => {

            const select = $('#usuarioRol').empty().append('<option value="">Seleccione…</option>');

            dtRoles.clear();

            data.forEach(r => {
                select.append(`<option value="${r.rolId}">${r.nombreRol}</option>`);

                dtRoles.row.add([
                    r.rolId,
                    r.nombreRol,
                    r.descripcion,
                    `
          <button class="btn btn-sm btn-warning" onclick="editarRol(${r.rolId},'${r.nombreRol}','${r.descripcion}')">
            <i class="fas fa-edit"></i>
          </button>
          <button class="btn btn-sm btn-danger" onclick="eliminarRol(${r.rolId})">
            <i class="fas fa-trash-alt"></i>
          </button>
          `
                ]);
            });
            dtRoles.draw();
        });
}

function saveRol(e) {
    e.preventDefault();
    const payload = {
        entity: 'role',
        action: $('#rolId').val() ? 'update' : 'add',
        rolId: $('#rolId').val(),
        nombreRol: $('#rolNombre').val(),
        descripcion: $('#rolDescription').val()
    };

    fetch(api, { method: 'POST', body: JSON.stringify(payload) })
        .then(r => r.json())
        .then(() => {
            listarRoles();
            bootstrap.Modal.getInstance('#rolModal').hide();
            $('#rolForm')[0].reset();
        });
}

function editarRol(id, nombre, descripcion) {
    $('#rolId').val(id);
    $('#rolNombre').val(nombre);
    $('#rolDescription').val(descripcion);
    $('#rolModalLabel').text('Editar Rol');
    new bootstrap.Modal('#rolModal').show();
}

function eliminarRol(id) {
    if (!confirm('¿Eliminar rol?')) return;
    fetch(api, {
        method: 'POST',
        body: JSON.stringify({ entity: 'role', action: 'delete', rolId: id })
    }).then(() => listarRoles());
}

function openRolModal() {
    $('#rolForm')[0].reset();
    $('#rolId').val('');
    $('#rolModalLabel').text('Agregar Rol');
}

/* ----------  USUARIOS  ---------- */
function listarUsuarios() {
    fetch(`${api}?entity=user`)
        .then(r => r.json())
        .then(data => {
            dtUsuarios.clear();
            data.forEach(u => {
                dtUsuarios.row.add([
                    u.usuarioId,
                    u.nombre,
                    u.correo,
                    u.nombreRol,
                    `
          <button class="btn btn-sm btn-warning" onclick="editarUsuario(${u.usuarioId},'${u.nombre}','${u.correo}',${u.rolId})">
            <i class="fas fa-edit"></i>
          </button>
          <button class="btn btn-sm btn-danger" onclick="eliminarUsuario(${u.usuarioId})">
            <i class="fas fa-trash-alt"></i>
          </button>
          `
                ]);
            });
            dtUsuarios.draw();
        });
}

function saveUsuario(e) {
    e.preventDefault();
    const payload = {
        entity: 'user',
        action: $('#usuarioId').val() ? 'update' : 'add',
        usuarioId: $('#usuarioId').val(),
        nombre: $('#usuarioNombre').val(),
        correo: $('#usuarioEmail').val(),
        password: $('#usuarioPassword')?.val(),
        rolId: $('#usuarioRol').val()
    };

    fetch(api, { method: 'POST', body: JSON.stringify(payload) })
        .then(r => r.json())
        .then(() => {
            listarUsuarios();
            bootstrap.Modal.getInstance('#usuarioModal').hide();
            $('#usuarioForm')[0].reset();
        });
}

function editarUsuario(id, nombre, correo) {
    $('#usuarioId').val(id);
    $('#usuarioNombre').val(nombre);
    $('#usuarioEmail').val(correo);
    $('#usuarioRol').val($('#usuarioRol option:contains("' + arguments[3] + '")').val());
    $('#usuarioModalLabel').text('Editar Usuario');
    new bootstrap.Modal('#usuarioModal').show();
}

function eliminarUsuario(id) {
    if (!confirm('¿Eliminar usuario?')) return;
    fetch(api, {
        method: 'POST',
        body: JSON.stringify({ entity: 'user', action: 'delete', usuarioId: id })
    }).then(() => listarUsuarios());
}

function openUsuarioModal() {
    $('#usuarioForm')[0].reset();
    $('#usuarioId').val('');
    $('#usuarioModalLabel').text('Agregar Usuario');
}

$(document).ready(() => {
    dtUsuarios = $('#tablaUsuarios').DataTable({
        language: { url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' }
    });

    dtRoles = $('#tablaRoles').DataTable({
        language: { url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' }
    });

    listarRoles();
    listarUsuarios()
})