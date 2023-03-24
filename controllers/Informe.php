<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;
class Informe extends Controller
{
    private $id_usuario;
    public function __construct()
    {
        parent::__construct();
        session_start();
        
        $this->id_usuario = $_SESSION['id_usuario'];
    }
    public function index()
    {
        $data['script'] = 'informe.js';
        $data['title'] = 'Informes en PDF';
        $data['informe'] = $this->model->getCaja($this->id_usuario);
        $this->views->getView('informe', 'index', $data);
    }

    public function listar()
    {
        $data = $this->model->getCajas();
        for ($i=0; $i < count($data); $i++) { 
            $data[$i]['accion'] = '<a href="'.BASE_URL.'cajas/historialRepote/'.$data[$i]['id'].'" target="_blank" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>';
        }
        echo json_encode($data);
        die();
    }

    public function getDatos()
    {
        $consultaVenta = $this->model->getVentas('total', $this->id_usuario);
        $ventas = ($consultaVenta['total'] != null) ? $consultaVenta['total'] : 0;

        $consultaDescuento = $this->model->getVentas('descuento', $this->id_usuario);
        $descuento = ($consultaDescuento['total'] != null) ? $consultaDescuento['total'] : 0;

        $consultaApartados = $this->model->getApartados($this->id_usuario);
        $apartados = ($consultaApartados['total'] != null) ? $consultaApartados['total'] : 0;
        
        $consultaCreditos = $this->model->getAbonos($this->id_usuario);
        $creditos = ($consultaCreditos['total'] != null) ? $consultaCreditos['total'] : 0;

        $consultaCompras = $this->model->getCompras($this->id_usuario);
        $compras = ($consultaCompras['total'] != null) ? $consultaCompras['total'] : 0;

        $consultaGastos = $this->model->getTotalGastos($this->id_usuario);
        $gastos = ($consultaGastos['total'] != null) ? $consultaGastos['total'] : 0;

        $montoInicial = $this->model->getCaja($this->id_usuario);

        $data['egresos'] = number_format($compras + $gastos, 2, '.', '');
        $data['ingresos'] = number_format(($ventas + $apartados + $creditos) - $descuento, 2, '.', '');
        $data['montoInicial'] = (!empty($montoInicial['monto_inicial'])) ? number_format($montoInicial['monto_inicial'], 2, '.', '') : 0;
        $data['gastos'] = number_format($gastos, 2, '.', '');
        $data['saldo'] = number_format(($data['ingresos'] + $data['montoInicial']) - $data['egresos'], 2, '.', '');

        $data['egresosDecimal'] = number_format($data['egresos'], 2);
        $data['ingresosDecimal'] = number_format($data['ingresos'], 2);
        $data['inicialDecimal'] = number_format($data['montoInicial'], 2);
        $data['gastosDecimal'] = number_format($data['gastos'], 2);
        $data['saldoDecimal'] = number_format($data['saldo'], 2);

        return $data;
    }
    public function reporte()
    {
        ob_start();

        $data['title'] = 'Reporte Actual';
        $data['actual'] = true;
        $data['empresa'] = $this->model->getEmpresa();
        $data['movimientos'] = $this->getDatos();
        $this->views->getView('cajas', 'reporte', $data);
        $html = ob_get_clean();
        $dompdf = new Dompdf();
        $options = $dompdf->getOptions();
        $options->set('isJavascriptEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'vertical');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream('reporte.pdf', array('Attachment' => false));
    }

    public function historialRepote($idCaja)
    {
        ob_start();
        $data['title'] = 'Reporte: ' . $idCaja;
        $data['idCaja'] = $idCaja;
        $data['actual'] = false;
        $data['empresa'] = $this->model->getEmpresa();
        $datos = $this->model->getHistorialCajas($idCaja);
        $data['movimientos']['inicialDecimal'] = $datos['monto_inicial'];
        $data['movimientos']['ingresosDecimal'] = $datos['monto_final'];
        $data['movimientos']['egresosDecimal'] = $datos['egresos'];
        $data['movimientos']['gastosDecimal'] = $datos['gastos'];
        $data['movimientos']['saldoDecimal'] = number_format($datos['monto_final'] + $datos['monto_inicial'], 2);
        $this->views->getView('cajas', 'reporte', $data);
        $html = ob_get_clean();
        $dompdf = new Dompdf();
        $options = $dompdf->getOptions();
        $options->set('isJavascriptEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'vertical');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream('reporte.pdf', array('Attachment' => false));
    }
}
