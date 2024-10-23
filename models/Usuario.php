<?php

namespace Model;

class Usuario extends ActiveRecord
{
    //Base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'telefono', 'admin', 'confirmado', 'token', 'password'];
    protected static $validaciones = [
        ['regex' => '/.{6,}/', 'mensaje' => 'La contraseña debe tener al menos 6 caracteres'],
        ['regex' => '/[A-Z]/', 'mensaje' => 'La contraseña debe tener al menos una letra mayúscula'],
        ['regex' => '/\d/', 'mensaje' => 'La contraseña debe tener al menos un número'],
        ['regex' => '/[@$#,!%*?&]/', 'mensaje' => 'La contraseña debe tener al menos un carácter especial (@$#,!%*?&)']
    ];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;
    public $password;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
        $this->password = $args['password'] ?? '';
    }

    //Mensajes de validacion para la creacion de una cuenta
    public function validarCuenta()
    {
        if (!$this->nombre) {
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }
        if (!$this->apellido) {
            self::$alertas['error'][] = 'El apellido es obligatorio';
        }
        if (!$this->telefono) {
            self::$alertas['error'][] = 'El telefono es obligatorio';
        }
        if (!$this->email) {
            self::$alertas['error'][] = 'El correo es obligatorio';
        }
        if (!$this->password) {
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        } else {
            foreach (self::$validaciones as $validacion):
                if (!preg_match($validacion['regex'], $this->password)) {
                    self::$alertas['error'][] = $validacion['mensaje'];
                }
            endforeach;
        }

        return self::$alertas;
    }

    public function validarLogin()
    {
        if (!$this->email) {
            self::$alertas['error'][] = 'El correo es obligatorio';
        }
        if (!$this->password) {
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }

        return self::$alertas;
    }

    public function validarEmail()
    {
        if (!$this->email) {
            self::$alertas['error'][] = 'El correo es obligatorio';
        }
        return self::$alertas;
    }
    public function validarPassword()
    {
        if (!$this->password) {
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        } else {
            foreach (self::$validaciones as $validacion):
                if (!preg_match($validacion['regex'], $this->password)) {
                    self::$alertas['error'][] = $validacion['mensaje'];
                }
            endforeach;
        }
        return self::$alertas;
    }

    //Se valida si el usuario existe, no se hace en active record ya que es especifico de usuario 
    public function existeUsuario()
    {
        $query = "SELECT * FROM " . self::$tabla . " WHERE email ='" . $this->email . "' LIMIT 1";

        $resultado = self::$db->query($query);

        if ($resultado->num_rows) {
            self::$alertas['error'][] = 'El Usuario ya esta registrado';
        }
        return $resultado;
    }

    public function hashPassword()
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken()
    {
        $this->token = uniqid();
    }

    public function comprobarPassAndConfirm($password)
    {
        $resultado = password_verify($password, $this->password); //Primero va la contraseña que el usuario ingreso y despues la de la base de datos

        if (!$resultado || !$this->confirmado) {
            self::$alertas['error'][] = 'La contraseña es incorrecta o tu cuenta no ha sido confirmada';
        } else {
            return true;
        }
    }
}
