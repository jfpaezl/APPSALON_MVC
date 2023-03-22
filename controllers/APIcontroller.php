<?php

namespace Controllers;
use Model\Servicio;
use Model\Cita;

class APIcontroller {
    public static function index  (){

        $servicio = Servicio::all();

        echo json_encode($servicio);
    }

    public static function guardar(){
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();
        echo json_encode($resultado);
    }
}