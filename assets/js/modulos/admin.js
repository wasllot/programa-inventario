const formulario = document.querySelector('#formulario');
const btnAccion = document.querySelector('#btnAccion');

const rif = document.querySelector('#rif');
const nombre = document.querySelector('#nombre');
const telefono = document.querySelector('#telefono');
const correo = document.querySelector('#correo');
const direccion = document.querySelector('#direccion');

const errorRif = document.querySelector('#errorRif');
const errorNombre = document.querySelector('#errorNombre');
const errorTelefono = document.querySelector('#errorTelefono');
const errorCorreo = document.querySelector('#errorCorreo');
const errorDireccion = document.querySelector('#errorDireccion');

document.addEventListener('DOMContentLoaded', function () {

    //INICIANDO UN EDITOR
    ClassicEditor
        .create(document.querySelector('#mensaje'), {
            toolbar: {
                items: [
                    'selectAll', '|',
                    'heading', '|',
                    'bold', 'italic',
                    'outdent', 'indent', '|',
                    'undo', 'redo',
                    'alignment', '|',
                    'link', 'blockQuote', 'insertTable', 'mediaEmbed'
                ],
                shouldNotGroupWhenFull: true
            },
        })
        .catch(error => {
            console.error(error);
        });

    //ACTUALIZAR DATOS
    formulario.addEventListener('submit', function (e) {
        e.preventDefault();
        errorRif.textContent = '';
        errorNombre.textContent = '';
        errorTelefono.textContent = '';
        errorCorreo.textContent = '';
        errorDireccion.textContent = '';
        if (rif.value == '') {
            errorRif.textContent = 'EL RIF ES REQUERIDO';
        } else if (nombre.value == '') {
            errorNombre.textContent = 'EL NOMBRE ES REQUERIDO';
        } else if (telefono.value == '') {
            errorTelefono.textContent = 'EL TELEFONO ES REQUERIDO';
        } else if (correo.value == '') {
            errorCorreo.textContent = 'EL CORREO ES REQUERIDO';
        } else if (direccion.value == '') {
            errorDireccion.textContent = 'LA DIRECCION ES REQUERIDA';
        } else {

            const url = base_url + 'admin/modificar';
            insertarRegistros(url, this, null, btAccion, false);
        }
    })

})

