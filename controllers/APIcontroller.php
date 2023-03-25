<?php

namespace Controllers;
use Model\CitasServicios;
use Model\Servicio;
use Model\Cita;

class APIcontroller {
    public static function index  (){

        $servicio = Servicio::all();

        echo json_encode($servicio);
    }

    public static function guardar(){
        //Almacena la cita y devuelve el id
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();
        $id = $resultado['id'];
        //Almacena la cita y el servicios con el Id de las citas
        $idServicios = explode(",", $_POST['servicios']);//explode() se usa para poder separar un 

        foreach ($idServicios as $idServicio) {
            $args = [
                'citaid' => $id,
                'servicioid' => $idServicio
            ];

            $citaServicio = new CitasServicios($args);
            $citaServicio->guardar();
        }

        //Retornamos una respuesta
        echo json_encode(['resultado' => $resultado]);
    }
}