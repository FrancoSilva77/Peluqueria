<?php

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class ApiController
{
  public static function index()
  {
    $servicios = Servicio::all();

    echo json_encode($servicios);
  }

  public static function guardar()
  {
    // * Almacena la cita y devuelve el id
    $cita = new Cita($_POST);
    $resultado = $cita->guardar();

    $id = $resultado['id'];

    // * Almacena la Cita y el Servicio

    
    // * Almacena los servicios con el ID de la Cita
    $idServicios = explode(",", $_POST['servicios']);
    foreach ($idServicios as $idServicio) {
      $args = [
        'citaId' => $id,
        'servicioId' => $idServicio
      ];
      $citaServicio = new CitaServicio($args);
      $citaServicio->guardar();
    }

    // $resultado = [
    //   'servicios' => $_POST['servicios']
    // ];

    // * Retornamos una respuesta
    echo json_encode(['resultado' => $resultado]);
  }
}
