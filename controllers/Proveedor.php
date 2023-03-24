<?php
class Proveedor extends Controller
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
        if ($_SESSION['rol'] == 2) {
            header('Location:' . BASE_URL . 'admin/permisos');
            exit;
        }
        $data['title'] = 'Proveedores';
        $data['script'] = 'proveedor.js';
        $this->views->getView('proveedor', 'index', $data);
    }
    public function listar()
    {
        
        $data = $this->model->getProveedores(1);
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['acciones'] = '<div>
            <button class="btn btn-danger" type="button" onclick="eliminarProveedor(' . $data[$i]['id'] . ')"><i class="fas fa-trash"></i></button>
            <button class="btn btn-info" type="button" onclick="editarProveedor(' . $data[$i]['id'] . ')"><i class="fas fa-edit"></i></button>
            </div>';
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function registrar()
    {
        if ($_SESSION['rol'] == 2) {
            header('Location:' . BASE_URL . 'admin/permisos');
            exit;
        }
        if (isset($_POST['rif']) && isset($_POST['nombre'])) {
            $id = strClean($_POST['id']);
            $rif = strClean($_POST['rif']);
            $nombre = strClean($_POST['nombre']);
            $telefono = strClean($_POST['telefono']);
            $correo = strClean($_POST['correo']);
            $direccion = strClean($_POST['direccion']);
            if (empty($rif)) {
                $res = array('msg' => 'EL RIF ES REQUERIDO', 'type' => 'warning');
            } else if (empty($nombre)) {
                $res = array('msg' => 'EL NOMBRE ES REQUERIDO', 'type' => 'warning');
            } else if (empty($telefono)) {
                $res = array('msg' => 'EL TELEFONO ES REQUERIDO', 'type' => 'warning');
            } else if (empty($correo)) {
                $res = array('msg' => 'EL CORREO ES REQUERIDO', 'type' => 'warning');
            } else if (empty($direccion)) {
                $res = array('msg' => 'LA DIRECCION ES REQUERIDA', 'type' => 'warning');
            } else {
                if ($id == '') {
                    $verificarRif = $this->model->getValidar('rif', $rif, 'registrar', 0);
                    if (empty($verificarRif)) {
                        $verificarTelefono = $this->model->getValidar('telefono', $telefono, 'registrar', 0);
                        if (empty($verificarTelefono)) {
                            $verificarCorreo = $this->model->getValidar('correo', $correo, 'registrar', 0);
                            if (empty($verificarCorreo)) {
                                $data = $this->model->registrar(
                                    $rif,
                                    $nombre,
                                    $telefono,
                                    $correo,
                                    $direccion
                                );
                                if ($data > 0) {
                                    $res = array('msg' => 'PROVEEDOR REGISTRADO', 'type' => 'success');
                                } else {
                                    $res = array('msg' => 'ERROR AL REGISTRAR', 'type' => 'error');
                                }
                            } else {
                                $res = array('msg' => 'CORREO YA REGISTRADO', 'type' => 'warning');
                            }
                        } else {
                            $res = array('msg' => 'TELÉFONO YA REGISTRADO', 'type' => 'warning');
                        }
                    } else {
                        $res = array('msg' => 'RIF YA REGISTRADO', 'type' => 'warning');
                    }
                } else {
                    $verificarRif = $this->model->getValidar('rif', $rif, 'actualizar', $id);
                    if (empty($verificarRif)) {
                        $verificarTelefono = $this->model->getValidar('telefono', $telefono, 'actualizar', $id);
                        if (empty($verificarTelefono)) {
                            $verificarCorreo = $this->model->getValidar('correo', $correo, 'actualizar', $id);
                            if (empty($verificarCorreo)) {
                                $data = $this->model->actualizar(
                                    $rif,
                                    $nombre,
                                    $telefono,
                                    $correo,
                                    $direccion,
                                    $id
                                );
                                if ($data > 0) {
                                    $res = array('msg' => 'PROVEEDOR MODIFICADO', 'type' => 'success');
                                } else {
                                    $res = array('msg' => 'ERROR AL MODIFICAR', 'type' => 'error');
                                }
                            } else {
                                $res = array('msg' => 'CORREO YA REGISTRADO', 'type' => 'warning');
                            }
                        } else {
                            $res = array('msg' => 'TELÉFONO YA REGISTRADO', 'type' => 'warning');
                        }
                    } else {
                        $res = array('msg' => 'RIF YA REGISTRADO', 'type' => 'warning');
                    }
                }
            }
        } else {
            $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
        }
        echo json_encode($res);
        die();
    }

    public function eliminar($idProveedor)
    {
        if ($_SESSION['rol'] == 2) {
            header('Location:' . BASE_URL . 'admin/permisos');
            exit;
        }        
        if (isset($_GET) && is_numeric($idProveedor)) {
            $data = $this->model->eliminar(0, $idProveedor);
            if ($data == 1) {
                $res = array('msg' => 'PROVEEDOR ELIMINADO', 'type' => 'success');
            } else {
                $res = array('msg' => 'ERROR ELIMINAR', 'type' => 'error');
            }
        } else {
            $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
        }
        echo json_encode($res);
        die();
    }

    public function editar($idProveedor)
    {
        if ($_SESSION['rol'] == 2) {
            header('Location:' . BASE_URL . 'admin/permisos');
            exit;
        }
        $data = $this->model->editar($idProveedor);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function inactivos()
    {
        if ($_SESSION['rol'] == 2) {
            header('Location:' . BASE_URL . 'admin/permisos');
            exit;
        }
        $data['title'] = 'Proveedores Inactivos';
        $data['script'] = 'proveedor-inactivos.js';
        $this->views->getView('proveedor', 'inactivos', $data);
    }

    public function listarInactivos()
    {  
        if ($_SESSION['rol'] == 2) {
            header('Location:' . BASE_URL . 'admin/permisos');
            exit;
        }      
        $data = $this->model->getProveedores(0);
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['acciones'] = '<div>
            <button class="btn btn-danger" type="button" onclick="restaurarProveedor(' . $data[$i]['id'] . ')"><i class="fas fa-check-circle"></i></button>
            </div>';
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function restaurar($idProveedor)
    {
        if ($_SESSION['rol'] == 2) {
            header('Location:' . BASE_URL . 'admin/permisos');
            exit;
        }
        if (isset($_GET) && is_numeric($idProveedor)) {
            $data = $this->model->eliminar(1, $idProveedor);
            if ($data == 1) {
                $res = array('msg' => 'PROVEEDOR RESTAURADO', 'type' => 'success');
            } else {
                $res = array('msg' => 'ERROR RESTUARAR', 'type' => 'error');
            }
        } else {
            $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
        }
        echo json_encode($res);
        die();
    }

    //buscar proveedores para la compra
    public function buscar()
    {
        $array = array();
        $valor = $_GET['term'];
        $data = $this->model->buscarPorNombre($valor);
        foreach ($data as $row) {
            $result['id'] = $row['id'];
            $result['label'] = $row['nombre'];
            $result['rif'] = $row['rif'];
            $result['direccion'] = $row['direccion'];
            array_push($array, $result);
        }
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        die();
    }
}
