<?php
require 'vendor/autoload.php';


use Dompdf\Dompdf;

class Productos extends Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['id_usuario'])) {
            header('Location:' . BASE_URL);
            exit;
        }
    }

    public function index()
    {
        $data['title'] = 'Productos';
        $data['script'] = 'productos.js';
        $data['medidas'] = $this->model->getDatos('medidas');
        $data['categorias'] = $this->model->getDatos('categorias');
        $this->views->getView('productos', 'index', $data);
    }

    public function listar()
    {
        $data = $this->model->getProductos(1);
        for ($i = 0; $i < count($data); $i++) {
            $foto = ($data[$i]['foto'] == null) ? 'assets/images/productos/default.png' : $data[$i]['foto'];
            $data[$i]['imagen'] = '<img class="img-thumbnail" src="' . $foto . '" width="50">';
            $data[$i]['acciones'] = '<div>
            <button class="btn btn-danger" type="button" onclick="eliminarProducto(' . $data[$i]['id'] . ')"><i class="fas fa-trash"></i></button>
            <button class="btn btn-info" type="button" onclick="editarProducto(' . $data[$i]['id'] . ')"><i class="fas fa-edit"></i></button>
            </div>';
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function registrar()
    {
        if (isset($_POST['codigo']) && isset($_POST['nombre'])) {
            $id = strClean($_POST['id']);
            $codigo = strClean($_POST['codigo']);
            $nombre = strClean($_POST['nombre']);
            $precio_compra = strClean($_POST['precio_compra']);
            $precio_venta = strClean($_POST['precio_venta']);
            $id_medida = strClean($_POST['id_medida']);
            $id_categoria = strClean($_POST['id_categoria']);
            $fotoActual = strClean($_POST['foto_actual']);
            $foto = $_FILES['foto'];
            $name = $foto['name'];
            $tmp = $foto['tmp_name'];

            $destino = null;
            if (!empty($name)) {
                $fecha = date('YmdHis');
                $destino = 'assets/images/productos/' . $fecha . '.jpg';
            } else if (!empty($fotoActual) && empty($name)) {
                $destino = $fotoActual;
            }
            if (empty($codigo)) {
                $res = array('msg' => 'EL CÓDIGO ES REQUERIDO', 'type' => 'warning');
            } else if (empty($nombre)) {
                $res = array('msg' => 'EL NOMBRE ES REQUERIDO', 'type' => 'warning');
            } else if (empty($precio_compra)) {
                $res = array('msg' => 'EL PRECIO COMPRA ES REQUERIDO', 'type' => 'warning');
            } else if (empty($precio_venta)) {
                $res = array('msg' => 'EL PRECIO VENTA ES REQUERIDO', 'type' => 'warning');
            } else if (empty($id_medida)) {
                $res = array('msg' => 'LA MEDIDA ES REQUERIDA', 'type' => 'warning');
            } else if (empty($id_categoria)) {
                $res = array('msg' => 'LA CATEGORIA ES REQUERIDA', 'type' => 'warning');
            } else {
                if ($id == '') {
                    $verificarCodigo = $this->model->Validar('codigo', $codigo, 'registrar', 0);
                    if (empty($verificarCodigo)) {
                        $verificarNombre = $this->model->Validar('descripcion', $nombre, 'registrar', 0);
                        if (empty($verificarNombre)) {
                            $data = $this->model->registrar(
                                $codigo,
                                $nombre,
                                $precio_compra,
                                $precio_venta,
                                $id_medida,
                                $id_categoria,
                                $destino
                            );
                            if ($data > 0) {
                                if (!empty($name)) {
                                    move_uploaded_file($tmp, $destino);
                                }
                                $res = array('msg' => 'PRODUCTO REGISTRADO', 'type' => 'success');
                            } else {
                                $res = array('msg' => 'ERROR AL REGISTRAR', 'type' => 'error');
                            }
                        } else {
                            $res = array('msg' => 'PRODUCTO YA REGISTRADO', 'type' => 'warning');
                        }
                    } else {
                        $res = array('msg' => 'CÓDIGO YA REGISTRADO', 'type' => 'warning');
                    }
                } else {
                    $verificarCodigo = $this->model->Validar('codigo', $codigo, 'actualizar', $id);
                    if (empty($verificarCodigo)) {
                        $verificarNombre = $this->model->Validar('descripcion', $nombre, 'actualizar', $id);
                        if (empty($verificarNombre)) {
                            $data = $this->model->actualizar(
                                $codigo,
                                $nombre,
                                $precio_compra,
                                $precio_venta,
                                $id_medida,
                                $id_categoria,
                                $destino,
                                $id
                            );
                            if ($data > 0) {
                                if (!empty($name)) {
                                    move_uploaded_file($tmp, $destino);
                                }
                                $res = array('msg' => 'PRODUCTO ACTUALIZADO', 'type' => 'success');
                            } else {
                                $res = array('msg' => 'ERROR AL ACTUALIZADO', 'type' => 'error');
                            }
                        } else {
                            $res = array('msg' => 'PRODUCTO YA REGISTRADO', 'type' => 'warning');
                        }
                    } else {
                        $res = array('msg' => 'CÓDIGO YA REGISTRADO', 'type' => 'warning');
                    }
                }
            }
        } else {
            $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
        }
        echo json_encode($res);
        die();
    }

    public function eliminar($idProducto)
    {
        if (isset($_GET) && is_numeric($idProducto)) {
            $data = $this->model->eliminar(0, $idProducto);
            if ($data == 1) {
                $res = array('msg' => 'PRODUCTO ELIMINADO', 'type' => 'success');
            } else {
                $res = array('msg' => 'ERROR AL ELIMINAR', 'type' => 'error');
            }
        } else {
            $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
        }
        echo json_encode($res);
        die();
    }

    public function editar($idProducto)
    {
        $data = $this->model->editar($idProducto);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function inactivos()
    {
        $data['title'] = 'Productos Inactivos';
        $data['script'] = 'productos-inactivos.js';
        $this->views->getView('productos', 'inactivos', $data);
    }

    public function listarInactivos()
    {
        $data = $this->model->getProductos(0);
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['imagen'] = '<img class="img-thumbnail" src="' . $data[$i]['foto'] . '" width="100">';
            $data[$i]['acciones'] = '<div>
            <button class="btn btn-danger" type="button" onclick="restaurarProducto(' . $data[$i]['id'] . ')"><i class="fas fa-check-circle"></i></button>
            </div>';
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function restaurar($idProducto)
    {
        if (isset($_GET) && is_numeric($idProducto)) {
            $data = $this->model->eliminar(1, $idProducto);
            if ($data == 1) {
                $res = array('msg' => 'PRODUCTO RESTAURADO', 'type' => 'success');
            } else {
                $res = array('msg' => 'ERROR AL RESTAURAR', 'type' => 'error');
            }
        } else {
            $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
        }
        echo json_encode($res);
        die();
    }

    //BUSCAR POR CÓDIGO
    public function buscarPorCodigo($valor)
    {
        $array = array('estado' => false, 'datos' => '');
        $data = $this->model->buscarPorCodigo($valor);
        if (!empty($data)) {
            $array['estado'] = true;
            $array['datos'] = $data;
        }
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        die();
    }

    //BUSCAR POR NOMBRE
    public function buscarPorNombre()
    {
        $array = array();
        $valor = $_GET['term'];
        $data = $this->model->buscarPorNombre($valor);
        foreach ($data as $row) {
            $result['id'] = $row['id'];
            $result['label'] = $row['descripcion'];
            $result['stock'] = $row['cantidad'];
            array_push($array, $result);
        }
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        die();
    }

    //MOSTRAR PRODUCTOS DESDE LOCALSTORAGE
    public function mostrarDatos()
    {
        $json = file_get_contents('php://input');
        $datos = json_decode($json, true);
        $array['productos'] = array();
        $totalCompra = 0;
        $totalVenta = 0;
        if (!empty($datos)) {
            foreach ($datos as $producto) {
                $result = $this->model->editar($producto['id']);
                $data['id'] = $result['id'];
                $data['nombre'] = $result['descripcion'];
                $data['precio_compra'] = $result['precio_compra'];
                $data['precio_venta'] = $result['precio_venta'];
                $data['cantidad'] = $producto['cantidad'];
                $subTotalCompra = $result['precio_compra'] * $producto['cantidad'];
                $subTotalVenta = $result['precio_venta'] * $producto['cantidad'];
                $data['subTotalCompra'] = number_format($subTotalCompra, 2);
                $data['subTotalVenta'] = number_format($subTotalVenta, 2);
                array_push($array['productos'], $data);
                $totalCompra += $subTotalCompra;
                $totalVenta += $subTotalVenta;
            }
        }
        $array['totalCompra'] = number_format($totalCompra, 2);
        $array['totalVenta'] = number_format($totalVenta, 2);
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        die();
    }
}
