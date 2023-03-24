<?php
class Usuarios extends Controller
{
    private $id_usuario;
    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['id_usuario'])) {
            header('Location:' . BASE_URL);
            exit;
        }
        $this->id_usuario = $_SESSION['id_usuario'];
    }
    public function index()
    {
        if ($_SESSION['rol'] == 2) {
            header('Location:' . BASE_URL . 'admin/permisos');
            exit;
        }
        $data['title'] = 'Usuarios';
        $data['script'] = 'usuarios.js';
        $this->views->getView('usuarios', 'index', $data);
    }
    public function listar()
    {
        if ($_SESSION['rol'] == 2) {
            header('Location:' . BASE_URL . 'admin/permisos');
            exit;
        }
        $data = $this->model->getUsuarios(1);
        for ($i = 0; $i < count($data); $i++) {

            if ($data[$i]['rol'] == 1) {
                $data[$i]['rol'] = '<span class="badge bg-success">ADMINISTRADOR</span>';
            } else {
                $data[$i]['rol'] = '<span class="badge bg-info">VENDEDOR</span>';
            }
            $data[$i]['acciones'] = '<div>
            <button class="btn btn-danger" type="button" onclick="eliminarUsuario(' . $data[$i]['id'] . ')"><i class="fas fa-times-circle"></i></button>
            <button class="btn btn-info" type="button" onclick="editarUsuario(' . $data[$i]['id'] . ')"><i class="fa-solid fa-pen-to-square"></i></button>
            </div>';
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    //MÉTODO PARA PODER REGISTRAR Y MODIFICAR 

    public function registrar()
    {
        if ($_SESSION['rol'] == 2) {
            header('Location:' . BASE_URL . 'admin/permisos');
            exit;
        }
        if (isset($_POST)) {
            if (empty($_POST['ci'])) {
                $res = array('msg' => 'LA CÉDULA ES REQUERIDA', 'type' => 'warning');
            } else if (empty($_POST['nombres'])) {
                $res = array('msg' => 'EL NOMBRE ES REQUERIDO', 'type' => 'warning');
            } else if (empty($_POST['apellidos'])) {
                $res = array('msg' => 'EL APELLIDO ES REQUERIDO', 'type' => 'warning');
            } else if (empty($_POST['correo'])) {
                $res = array('msg' => 'EL CORREO ES REQUERIDO', 'type' => 'warning');
            } else if (empty($_POST['telefono'])) {
                $res = array('msg' => 'EL TELÉFONO ES REQUERIDO', 'type' => 'warning');
            } else if (empty($_POST['direccion'])) {
                $res = array('msg' => 'LA DIRECCIÓN ES REQUERIDA', 'type' => 'warning');
            } else if (empty($_POST['contraseña'])) {
                $res = array('msg' => 'LA CONTRASEÑA ES REQUERIDA', 'type' => 'warning');
            } else if (empty($_POST['rol'])) {
                $res = array('msg' => 'EL ROL ES REQUERIDO', 'type' => 'warning');
            } else {
                $ci = strClean($_POST['ci']);
                $nombres = strClean($_POST['nombres']);
                $apellidos = strClean($_POST['apellidos']);
                $correo = strClean($_POST['correo']);
                $telefono = strClean($_POST['telefono']);
                $direccion = strClean($_POST['direccion']);
                $contraseña = strClean($_POST['contraseña']);
                $rol = strClean($_POST['rol']);
                $id = strClean($_POST['id']);

                if ($id == '') {

                    $hash = password_hash($contraseña, PASSWORD_DEFAULT);
                    //VERIFICAR SI EXISTE LOS DATOS
                    $verificarci = $this->model->Validar('ci', $ci, 'registrar', 0);
                    if (empty($verificarci)) {
                        $verificarcorreo = $this->model->Validar('correo', $correo, 'registrar', 0);
                        if (empty($verificarcorreo)) {
                            $verificartlf = $this->model->Validar('telefono', $telefono, 'registrar', 0);
                            if (empty($verificartlf)) {
                                $data = $this->model->registrar(
                                    $ci,
                                    $nombres,
                                    $apellidos,
                                    $correo,
                                    $telefono,
                                    $direccion,
                                    $hash,
                                    $rol
                                );
                                if ($data > 0) {
                                    $res = array('msg' => 'USUARIO REGISTRADO', 'type' => 'success');
                                } else {
                                    $res = array('msg' => 'ERROR AL REGISTRAR', 'type' => 'error');
                                }
                            } else {
                                $res = array('msg' => 'TELÉFONO YA REGISTRADO', 'type' => 'warning');
                            }
                        } else {
                            $res = array('msg' => 'CORREO YA REGISTRADO', 'type' => 'warning');
                        }
                    } else {
                        $res = array('msg' => 'CÉDULA YA REGISTRADA', 'type' => 'warning');
                    }
                } else {
                    //VERIFICAR SI EXISTE LOS DATOS
                    $verificarci = $this->model->Validar('ci', $ci, 'modificar', $id);
                    if (empty($verificarci)) {
                        $verificarcorreo = $this->model->Validar('correo', $correo, 'modificar', 0);
                        if (empty($verificarcorreo)) {
                            $verificartlf = $this->model->Validar('telefono', $telefono, 'modificar', $id);
                            if (empty($verificartlf)) {
                                $data = $this->model->actualizar(
                                    $ci,
                                    $nombres,
                                    $apellidos,
                                    $correo,
                                    $telefono,
                                    $direccion,
                                    $rol,
                                    $id
                                );
                                if ($data > 0) {
                                    $res = array('msg' => 'USUARIO ACTUALIZADO', 'type' => 'success');
                                } else {
                                    $res = array('msg' => 'ERROR AL ALTUALIZAR', 'type' => 'error');
                                }
                            } else {
                                $res = array('msg' => 'TELÉFONO YA REGISTRADO', 'type' => 'warning');
                            }
                        } else {
                            $res = array('msg' => 'CORREO YA REGISTRADO', 'type' => 'warning');
                        }
                    } else {
                        $res = array('msg' => 'CÉDULA YA REGISTRADA', 'type' => 'warning');
                    }
                }
            }
        } else {
            $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);

        die();
    }

    //MÉTODO PARA ELIMINAR REGISTRO DE USUARIO

    public function eliminar($id)
    {
        if ($_SESSION['rol'] == 2) {
            header('Location:' . BASE_URL . 'admin/permisos');
            exit;
        }
        if (isset($_GET)) {
            if (is_numeric($id)) {
                $data = $this->model->eliminar(0, $id);
                if ($data == 1) {
                    $res = array('msg' => 'USUARIO ELIMINADO', 'type' => 'success');
                } else {
                    $res = array('msg' => 'ERROR AL ELIMINAR', 'type' => 'error');
                }
            } else {
                $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
            }
        } else {
            $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function editar($id)
    {
        if ($_SESSION['rol'] == 2) {
            header('Location:' . BASE_URL . 'admin/permisos');
            exit;
        }
        $data = $this->model->editar($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    //USUARIO INACTIVOS 

    public function inactivos()
    {
        if ($_SESSION['rol'] == 2) {
            header('Location:' . BASE_URL . 'admin/permisos');
            exit;
        }
        $data['title'] = 'Usuarios Inactivos';
        $data['script'] = 'usuarios-inactivos.js';
        $this->views->getView('usuarios', 'inactivos', $data);
    }

    public function listarInactivos()
    {
        if ($_SESSION['rol'] == 2) {
            header('Location:' . BASE_URL . 'admin/permisos');
            exit;
        }
        $data = $this->model->getUsuarios(0);
        for ($i = 0; $i < count($data); $i++) {

            if ($data[$i]['rol'] == 1) {
                $data[$i]['rol'] = '<span class="badge bg-success">ADMINISTRADOR</span>';
            } else {
                $data[$i]['rol'] = '<span class="badge bg-info">VENDEDOR</span>';
            }
            $data[$i]['acciones'] = '<div>
            <button class="btn btn-danger" type="button" onclick="restaurarUsuario(' . $data[$i]['id'] . ')"><i class="fas fa-check-circle"></i></button>
            </div>';
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function restaurar($id)
    {
        if ($_SESSION['rol'] == 2) {
            header('Location:' . BASE_URL . 'admin/permisos');
            exit;
        }
        if (isset($_GET)) {
            if (is_numeric($id)) {
                $data = $this->model->eliminar(1, $id);
                if ($data == 1) {
                    $res = array('msg' => 'USUARIO RESTAURADO', 'type' => 'success');
                } else {
                    $res = array('msg' => 'ERROR AL RESTAURAR', 'type' => 'error');
                }
            } else {
                $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
            }
        } else {
            $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die();
    }

    //PERFIL DE USUARIO

    public function profile()
    {
        $data['title'] = 'Datos del usuario';
        $data['script'] = 'profile.js';
        $data['usuario'] = $this->model->editar($this->id_usuario);
        $this->views->getView('usuarios', 'perfil', $data);
    }

    public function modificarDatos()
    {
        $ci = strClean($_POST['ciPerfil']);
        $nombre = strClean($_POST['nombrePerfil']);
        $apellido = strClean($_POST['apellidoPerfil']);
        $correo = strClean($_POST['correoPerfil']);
        $telefono = strClean($_POST['telefonoPerfil']);
        $direccion = strClean($_POST['direccionPerfil']);
        $claveNueva = strClean($_POST['claveNueva']);
        $claveActual = strClean($_POST['claveActual']);

        $foto = $_FILES['fotoPerfil'];
        $name = $foto['name'];
        $tmp = $foto['tmp_name'];

        $verificarPerfil = $this->model->editar($this->id_usuario);

        $destino = $verificarPerfil['perfil'];

        if (!empty($name)) {
            if (file_exists($destino)) {
                unlink($destino);
            }
            $perfil = date('YmdHis') . $correo . '.jpg';
            $destino = 'assets/images/perfil/' . $perfil;
        }


        if (empty($ci)) {
            $res = array('msg' => 'LA CI ES REQUERIDA', 'type' => 'warning');
        } else if (empty($nombre)) {
            $res = array('msg' => 'EL NOMBRE ES REQUERIDO', 'type' => 'warning');
        } else if (empty($apellido)) {
            $res = array('msg' => 'EL APELLIDO ES REQUERIDO', 'type' => 'warning');
        } else if (empty($correo)) {
            $res = array('msg' => 'EL CORREO ES REQUERIDO', 'type' => 'warning');
        } else if (empty($telefono)) {
            $res = array('msg' => 'EL TELÉFONO ES REQUERIDO', 'type' => 'warning');
        } else if (empty($direccion)) {
            $res = array('msg' => 'LA DIRECCIÓN ES REQUERIDA', 'type' => 'warning');
        } else {
            $verificarClave = $this->model->editar($this->id_usuario);
            if (empty($claveNueva)) {
                $hash = $verificarClave['clave'];
                //VERIFICAR SI EXISTE LOS DATOS
                $verificarci = $this->model->Validar('ci', $ci, 'actualizar', $this->id_usuario);
                if (empty($verificarci)) {
                    $verificarcorreo = $this->model->Validar('correo', $correo, 'actualizar', $this->id_usuario);
                    if (empty($verificarcorreo)) {
                        $verificartlf = $this->model->Validar('telefono', $telefono, 'actualizar', $this->id_usuario);
                        if (empty($verificartlf)) {
                            $data = $this->model->modificarDatos($ci, $nombre, $apellido, $correo, $telefono, $direccion, $destino, $hash, $this->id_usuario);
                            if ($data == 1) {
                                if (!empty($name)) {
                                    move_uploaded_file($tmp, $destino);
                                }
                                $res = array('msg' => 'DATOS MODIFICADOS', 'type' => 'success', 'clave' => false);
                            } else {
                                $res = array('msg' => 'ERROR AL MODIFICAR', 'type' => 'error');
                            }
                        } else {
                            $res = array('msg' => 'TELÉFONO YA REGISTRADO', 'type' => 'warning');
                        }
                    } else {
                        $res = array('msg' => 'CORREO YA REGISTRADO', 'type' => 'warning');
                    }
                } else {
                    $res = array('msg' => 'CEDULA YA REGISTRADA', 'type' => 'warning');
                }
            } else {
                if (password_verify($claveActual, $verificarClave['clave'])) {

                    //VERIFICAR SI EXISTE LOS DATOS
                    $verificarci = $this->model->Validar('ci', $ci, 'actualizar', $this->id_usuario);
                    if (empty($verificarci)) {
                        $verificarcorreo = $this->model->Validar('correo', $correo, 'actualizar', $this->id_usuario);
                        if (empty($verificarcorreo)) {
                            $verificartlf = $this->model->Validar('telefono', $telefono, 'actualizar', $this->id_usuario);
                            if (empty($verificartlf)) {
                                $hash = password_hash($claveNueva, PASSWORD_DEFAULT);
                                $data = $this->model->modificarDatos($ci, $nombre, $apellido, $correo, $telefono, $direccion, $destino, $hash, $this->id_usuario);
                                if ($data == 1) {
                                    if (!empty($name)) {
                                        move_uploaded_file($tmp, $destino);
                                    }
                                    $res = array('msg' => 'DATOS MODIFICADOS', 'type' => 'success', 'clave' => true);
                                } else {
                                    $res = array('msg' => 'ERROR AL MODIFICAR', 'type' => 'error');
                                }
                            } else {
                                $res = array('msg' => 'TELÉFONO YA REGISTRADO', 'type' => 'warning');
                            }
                        } else {
                            $res = array('msg' => 'CORREO YA REGISTRADO', 'type' => 'warning');
                        }
                    } else {
                        $res = array('msg' => 'CEDULA YA REGISTRADA', 'type' => 'warning');
                    }
                } else {
                    $res = array('msg' => 'CONTRASELA ACTUAL INCORRECTA', 'type' => 'warning');
                }
            }
        }

        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function salir()
    {
        $evento = 'Cierre de Sesión';
        $ip = $_SERVER['REMOTE_ADDR'];
        $detalle = $_SERVER['HTTP_USER_AGENT'];
        $acceso = $this->model->registrarAcceso($evento, $ip, $detalle);
        if ($acceso > 0) {  
        session_destroy();
        header('Location: ' . BASE_URL);
        } 
    }
}
