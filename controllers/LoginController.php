<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController
{
  public static function login(Router $router)
  {
    $router->render('auth/login', []);
  }

  public static function logout()
  {
    echo "Desde logout";
  }

  public static function olvide(Router $router)
  {
    $router->render('auth/olvide-password', []);
  }

  public static function recuperar()
  {
    echo "Desde recuperar";
  }

  public static function crear(Router $router)
  {
    $usuario = new Usuario($_POST);

    // Alertas vacias
    $alertas = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $usuario->sincronizar();
      $alertas = $usuario->ValidarNuevaCuenta();

      // Revisar que alerta este vacio
      if (empty($alertas)) {
        // Verificar que el usuario no este registrado
        $resultado = $usuario->existeUsuario();

        if ($resultado->num_rows) {
          $alertas = Usuario::getAlertas();
        } else {
          // Hashear el password
          $usuario->hashPassword();

          // Generar un token único
          $usuario->crearToken();

          // Enviar el email
          $email = new Email($usuario->nombre, $usuario->email, $usuario->token);

          $email->enviarConfirmacion();

          debuguear($usuario);
        }
      }
    }

    $router->render('auth/crear-cuenta', [
      'usuario' => $usuario,
      'alertas' => $alertas
    ]);
  }
}
