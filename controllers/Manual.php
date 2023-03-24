<?php

require 'vendor/autoload.php';

class Manual extends Controller
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
        $data['title'] = 'Manual';
        $this->views->getView('manual', 'index', $data);
    }



}

?>