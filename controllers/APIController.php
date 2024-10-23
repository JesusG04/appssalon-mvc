<?php

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController
{
    public static function index()
    {
        $servicios = Servicio::all();
        echo json_encode($servicios);
    }
    public static function save()
    {
        //Almacena la cita y devuelve el ID
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();
        $id = $resultado['id'];

        //Almacena los servicios con el ID de la cita
        $idServicios = explode(",",$_POST['servicios']);

        

        foreach($idServicios as $idServicio){
            $args = [
                'citaid' => $id,
                'servicioid' => $idServicio
            ];
            
            $citaServicio = new CitaServicio($args);
            
            $citaServicio->guardar();
        }
        

        echo json_encode(['resultado' => $resultado]);
    }

    public static function delete (){
        $id = $_POST['id'];
        $cita = Cita::find($id);
        $cita->eliminar();
        header('Location:'.$_SERVER['HTTP_REFERER']);

    }
}
