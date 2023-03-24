const formulario = document.querySelector('#formulario');

let myChart;

if (formulario && document.querySelector('#reporteMovimiento')) {

    //cargar datos con el plugin datatables
    $('#tblAperturaCierre').DataTable({
        ajax: {
            url: base_url + 'cajas/listar',
            dataSrc: ''
        },
        columns: [
            { data: 'monto_inicial' },
            { data: 'fecha_apertura' },
            { data: 'fecha_cierre' },
            { data: 'monto_final' },
            { data: 'total_ventas' },
            { data: 'nombre' },
            { data: 'accion' }
        ],
        language: {
            url: base_url + 'assets/js/espanol.json'
        },
        dom,
        buttons,
        responsive: true,
        order: [[0, 'asc']],
    });
}