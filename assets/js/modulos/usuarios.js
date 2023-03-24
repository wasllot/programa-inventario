let tblUsuarios;
const formulario = document.querySelector('#formulario');
const ci = document.querySelector('#ci');
const nombres = document.querySelector('#nombres');
const apellidos = document.querySelector('#apellidos');
const correo = document.querySelector('#correo');
const telefono = document.querySelector('#telefono');
const direccion = document.querySelector('#direccion');
const contraseña = document.querySelector('#contraseña');
const rol = document.querySelector('#rol');
const id = document.querySelector('#id');

//ELEMENTOS PARA MOSTRAR ERRORES 

const errorCi = document.querySelector('#errorCi');
const errorNombre = document.querySelector('#errorNombre');
const errorApellido = document.querySelector('#errorApellido');
const errorCorreo = document.querySelector('#errorCorreo');
const errorTelefono = document.querySelector('#errorTelefono');
const errorDireccion = document.querySelector('#errorDireccion');
const errorContraseña = document.querySelector('#errorContraseña');
const errorRol = document.querySelector('#errorRol');

const btnAccion = document.querySelector('#btnAccion');
const btnNuevo = document.querySelector('#btnNuevo');

document.addEventListener('DOMContentLoaded', function () {
    //CARGAR DATOS CON EL PLUGIN DATATABLES

    tblUsuarios = $('#tblUsuarios').DataTable({
        ajax: {
            url: base_url + 'usuarios/listar',
            dataSrc: ''
        },
        columns: [

            { data: 'nombres' },
            { data: 'ci' },
            { data: 'correo' },
            { data: 'telefono' },
            { data: 'direccion' },
            { data: 'rol' },
            { data: 'acciones' }
        ],

        language: {
            url: base_url + 'assets/js/espanol.json'
        },
        dom,
        buttons,
        responsive: true,
        order: [[0, 'asc']],

    });
    //LIMPIAR CAMPOS
    btnNuevo.addEventListener('click', function () {
        id.value = '';
        btnAccion.textContent = 'Registrar'
        contraseña.removeAttribute('readonly');
        formulario.reset();
        ci.focus();
        limpiarCampos();
    })

    //REGISTRAR USUARIOS
    formulario.addEventListener('submit', function (e) {
        e.preventDefault();
        limpiarCampos();        
        if (ci.value == '') {
            errorCi.textContent = 'LA CEDULA ES REQUERIDA';
        } else if (nombres.value == '') {
            errorNombre.textContent = 'EL NOMBRE ES REQUERIDO';
        } else if (apellidos.value == '') {
            errorApellido.textContent = 'EL APELLIDO ES REQUERIDO';
        } else if (correo.value == '') {
            errorCorreo.textContent = 'EL CORREO ES REQUERIDO';
        } else if (telefono.value == '') {
            errorTelefono.textContent = 'EL TELEFONO ES REQUERIDO';
        } else if (direccion.value == '') {
            errorDireccion.textContent = 'LA DIRECCION ES REQUERIDO';
        } else if (contraseña.value == '') {
            errorContraseña.textContent = 'LA CONTRASEÑA ES REQUERIDO';
        } else if (rol.value == '') {
            errorRol.textContent = 'EL PERFIL ES REQUERIDO';
        } else {


            const url = base_url + 'usuarios/registrar';
            insertarRegistros(url, this, tblUsuarios, btnAccion, true);

        }

    })

})

//FUNCIÓN PARA ELIMINAR USUARIO

function eliminarUsuario(idUsuario) {
    const url = base_url + 'usuarios/eliminar/' + idUsuario;
    eliminarRegistros(url, tblUsuarios);
}

//FUNCIÓN PARA EDITAR UN USUARIO

function editarUsuario(idUsuario) {
    limpiarCampos();
    const url = base_url + 'usuarios/editar/' + idUsuario;
    //hacer una instancia del objeto XMLHttpRequest 
    const http = new XMLHttpRequest();
    //Abrir una Conexion - POST - GET
    http.open('GET', url, true);
    //Enviar Datos
    http.send();
    //verificar estados
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            id.value = res.id;
            ci.value = res.ci;
            nombres.value = res.nombre;
            apellidos.value = res.apellido;
            correo.value = res.correo;
            telefono.value = res.telefono;
            direccion.value = res.direccion;
            rol.value = res.rol;
            contraseña.value = '00000';
            contraseña.setAttribute('readonly', 'readonly');
            btnAccion.textContent = 'Actualizar';
            const firstTabEl = document.querySelector('#nav-tab button:last-child')
            const firstTab = new bootstrap.Tab(firstTabEl)
            firstTab.show()

        }
    }
}

function limpiarCampos() {
    errorCi.textContent = '';
    errorNombre.textContent = '';
    errorApellido.textContent = '';
    errorCorreo.textContent = '';
    errorTelefono.textContent = '';
    errorDireccion.textContent = '';
    errorContraseña.textContent = '';
    errorRol.textContent = '';
}