<?php
require 'vendor/autoload.php';

use Dompdf\Dompdf;

class Ventas extends Controller
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
        $data['title'] = 'Ventas';
        $data['script'] = 'ventas.js';
        $data['busqueda'] = 'busqueda.js';
        $data['carrito'] = 'posVenta';
        $resultSerie = $this->model->getSerie();
        $serie = ($resultSerie['total'] == null) ? 1 : $resultSerie['total'] + 1;
        $data['serie'] = $this->generate_numbers($serie, 1, 4);
        $this->views->getView('ventas', 'index', $data);
    }

    public function registrarVenta()
    {
        $json = file_get_contents('php://input');
        $datos = json_decode($json, true);
        $array['productos'] = array();
        $total = 0;
        if (!empty($datos['productos'])) {
            $fecha = date('Y-m-d');
            $metodo = $datos['metodo'];

            $resultSerie = $this->model->getSerie();
            $numSerie = ($resultSerie['total'] == null) ? 1 : $resultSerie['total'] + 1;

            $serie = $this->generate_numbers($numSerie, 1, 4);
            $descuento = (!empty($datos['descuento'])) ? $datos['descuento'] : 0;
            $idCliente = $datos['idCliente'];
            if (empty($idCliente)) {
                $res = array('msg' => 'EL CLIENTE ES REQUERIDO', 'type' => 'warning');
            } else if (empty($metodo)) {
                $res = array('msg' => 'EL METODO ES REQUERIDO', 'type' => 'warning');
            } else {
                foreach ($datos['productos'] as $producto) {
                    $result = $this->model->getProducto($producto['id']);
                    $data['id'] = $result['id'];
                    $data['nombre'] = $result['descripcion'];
                    $data['precio'] = $result['precio_venta'];
                    $data['cantidad'] = $producto['cantidad'];
                    $subTotal = $result['precio_venta'] * $producto['cantidad'];
                    array_push($array['productos'], $data);
                    $total += $subTotal;
                    //actualizar stock
                    $nuevaCantidad = $result['cantidad'] - $producto['cantidad'];
                    $totalVentas = $result['ventas'] + $producto['cantidad'];
                    $this->model->actualizarStock($nuevaCantidad, $totalVentas, $result['id']);
                
                }
                $datosProductos = json_encode($array['productos']);
                $venta = $this->model->registrarVenta($datosProductos, $total, $fecha, $metodo, $descuento, $serie[0], $idCliente, $this->id_usuario);

                if ($venta > 0) {
                    if ($metodo == 'CREDITO') {
                        $monto = $total - $descuento;
                        $this->model->registrarCredito($monto, $fecha, $venta);
                    }         
                    
                    //Movimientos
                    $movimiento = 'Venta N°: ' . $venta;

                    foreach ($datos['productos'] as $producto) {
                        $result = $this->model->getProducto($producto['id']);
                        $nuevaCantidad = $result['cantidad'] - $producto['cantidad'];
                        $this->model->registrarMovimiento($movimiento, 'salida', $producto['cantidad'], $nuevaCantidad, $producto['id'], $this->id_usuario);
                    }
                    

                    $res = array('msg' => 'VENTA GENERADA', 'type' => 'success', 'idVenta' => $venta);
                } else {
                    $res = array('msg' => 'ERROR AL GENERAR VENTA', 'type' => 'error');
                }
            }
        } else {
            $res = array('msg' => 'CARRITO VACIO', 'type' => 'warning');
        }
        echo json_encode($res);
        die();
    }

    public function reporte($datos)
    {
        ob_start();
        $array = explode(',', $datos);
        $tipo = $array[0];
        $idVenta = $array[1];

        $data['title'] = 'Reporte';
        $data['empresa'] = $this->model->getEmpresa();
        $data['venta'] = $this->model->getVenta($idVenta);
        if (empty($data['venta'])) {
            echo 'Pagina no Encontrada';
            exit;
        }
        $this->views->getView('ventas', $tipo, $data);
        $html = ob_get_clean();
        $dompdf = new Dompdf();
        $options = $dompdf->getOptions();
        $options->set('isJavascriptEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);
        $dompdf->loadHtml($html);

        if ($tipo == 'ticket') {
            $dompdf->setPaper(array(0, 0, 130, 841), 'portrait');
        } else {
            $dompdf->setPaper('A4', 'vertical');
        }

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream('reporte.pdf', array('Attachment' => false));
    }

    public function listar()
    {
        $data = $this->model->getVentas();
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['acciones'] = '<div>
                <a class="btn btn-warning" href="#" onclick="anularVenta(' . $data[$i]['id'] . ')"><i class="fas fa-trash"></i></a>
                <a class="btn btn-danger" href="#" onclick="verReporte(' . $data[$i]['id'] . ')"><i class="fas fa-file-pdf"></i></a>
                </div>';
            } else {
                $data[$i]['acciones'] = '<div>
                <span class="badge bg-info">Anulado</span>
                <a class="btn btn-danger" href="#" onclick="verReporte(' . $data[$i]['id'] . ')"><i class="fas fa-file-pdf"></i></a>
                </div>';
            }
        }
        echo json_encode($data);
        die();
    }

    public function anular($idVenta)
    {
        if (isset($_GET) && is_numeric($idVenta)) {
            $data = $this->model->anular($idVenta);
            if ($data == 1) {
                $resultVenta = $this->model->getVenta($idVenta);
                $ventaProducto = json_decode($resultVenta['productos'], true);
                foreach ($ventaProducto as $producto) {
                    $result = $this->model->getProducto($producto['id']);
                    $nuevaCantidad = $result['cantidad'] + $producto['cantidad'];
                    $totalVentas = $result['ventas'] - $producto['cantidad'];
                    $this->model->actualizarStock($nuevaCantidad, $totalVentas, $producto['id']);

                    //movimientos
                    $movimiento = 'Devolución Venta N°: ' . $idVenta;
                    $this->model->registrarMovimiento($movimiento, 'entrada', $producto['cantidad'], $nuevaCantidad, $producto['id'], $this->id_usuario);
                }
                if ($resultVenta['metodo'] == 'CREDITO') {
                    $this->model->anularCredito($idVenta);
                }
                $res = array('msg' => 'VENTA ANULADO', 'type' => 'success');
            } else {
                $res = array('msg' => 'ERROR AL ANULAR', 'type' => 'error');
            }
        } else {
            $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
        }
        echo json_encode($res);
        die();
    }

    public function verificarStock($idProducto)
    {
        $data = $this->model->getProducto($idProducto);
        echo json_encode($data);
        die();
    }

    function generate_numbers($start, $count, $digits)
    {
        $result = array();
        for ($n = $start; $n < $start + $count; $n++) {
            $result[] = str_pad($n, $digits, "0", STR_PAD_LEFT);
        }
        return $result;
    }
}
