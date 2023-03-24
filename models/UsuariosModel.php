<?php
class UsuariosModel extends Query{
    public function __construct() {
        parent::__construct();
    }
    public function getUsuarios($estado)
    {
        $sql = "SELECT id, CONCAT(nombre, ' ', apellido) AS nombres, ci, correo, telefono, direccion, rol FROM usuarios WHERE estado = $estado";
        return $this->selectAll($sql);
    }
    public function registrar($ci, $nombres, $apellidos, $correo, 
    $telefono, $direccion, $contraseña, $rol)
    {
        $sql = "INSERT INTO usuarios (ci, nombre, apellido, correo, telefono, 
        direccion, clave, rol) VALUES (?,?,?,?,?,?,?,?)";
        $array = array($ci, $nombres, $apellidos, $correo, 
        $telefono, $direccion, $contraseña, $rol);
        return $this->insertar($sql, $array);
    }
    public function Validar($campo, $valor, $accion, $id)
    {
        if ($accion == 'registrar' && $id == 0) {
            $sql = "SELECT id, ci, correo, telefono FROM usuarios WHERE $campo = '$valor'";
        }else {
            $sql = "SELECT id, ci, correo, telefono FROM usuarios WHERE $campo = '$valor' AND id != $id";
        }
        
        return $this->select($sql);
    }
    public function eliminar($estado, $id)
    {
        $sql = "UPDATE usuarios SET estado = ? WHERE id = ?";
        $array = array($estado, $id);
        return $this->save($sql, $array);
    }

    public function editar($id)
    {
        $sql = "SELECT id, ci, nombre, apellido, correo, telefono, direccion, perfil, clave, fecha, rol FROM usuarios WHERE id = $id";
        return $this->select($sql);
    }

    public function actualizar($ci, $nombres, $apellidos, $correo, 
    $telefono, $direccion, $rol, $id)
    {
        $sql = "UPDATE usuarios SET ci=?, nombre=?, apellido=?, correo=?, telefono=?, 
        direccion=?, rol=? WHERE id=?";
        $array = array($ci, $nombres, $apellidos, $correo, 
        $telefono, $direccion, $rol, $id);
        return $this->save($sql, $array);
    }

    public function modificarDatos($ci ,$nombre, $apellido, $correo, 
    $telefono, $direccion, $perfil, $contraseña, $id)
    {
        $sql = "UPDATE usuarios SET ci=? ,nombre=?, apellido=?, correo=?, telefono=?, 
        direccion=?, perfil=?, clave=? WHERE id=?";
        $array = array($ci ,$nombre, $apellido, $correo, 
        $telefono, $direccion, $perfil, $contraseña, $id);
        return $this->save($sql, $array);
    }

    //registrar log
    public function registrarAcceso($evento, $ip, $detalle)
    {
        $sql = "INSERT INTO acceso (evento, ip, detalle) VALUES (?,?,?)";
        $array = array($evento, $ip, $detalle);
        return $this->insertar($sql, $array);
    }
}
?>