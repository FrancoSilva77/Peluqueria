<?php

namespace Model;

class Usuario extends ActiveRecord
{
  // Base de datos
  protected static $tabla = 'usuarios';
  protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token'];

  public $id;
  public $nombre;
  public $apellido;
  public $email;
  public $password;
  public $telefono;
  public $admin;
  public $confirmado;
  public $token;

  public function __construct($args = [])
  {
    $this->id = $args['id'] ?? null;
    $this->nombre = $args['nombre'] ?? '';
    $this->apellido = $args['apellido'] ?? '';
    $this->email = $args['email'] ?? null;
    $this->password = $args['password'] ?? null;
    $this->telefono = $args['telefono'] ?? null;
    $this->admin = $args['admin'] ?? null;
    $this->confirmado = $args['confirmado'] ?? null;
    $this->token = $args['token'] ?? '';
  }

  // Mensajes de validación para la creacion de una cuenta
  public function validarNuevaCuenta()
  {
    if (!$this->nombre) {
      self::$alertas['error'][] = 'El Nombre es obligatorio';
    }

    if (!$this->apellido) {
      self::$alertas['error'][] = 'El Apellido es obligatorio';
    }

    if (!$this->email) {
      self::$alertas['error'][] = 'El Email es obligatorio';
    }

    if (!$this->password) {
      self::$alertas['error'][] = 'El password es obligatorio';
    }

    if (strlen($this->password) < 6) {
      self::$alertas['error'][] = 'El password debe contenener al menos 6 caracteres';
    }

    return self::$alertas;
  }

  // Revisa si el usuario ya existe
  public function existeUsuario()
  {
    $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1 ";

    $resultado = self::$db->query($query);

    if ($resultado->num_rows) {
      self::$alertas['error'][] = 'El usuario ya está registrado';
    }

    return $resultado;
  }
}
