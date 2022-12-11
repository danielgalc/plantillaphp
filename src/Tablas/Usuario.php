<?php

namespace App\Tablas;

use PDO;

class Usuario extends Modelo
{
    public $id;
    public $usuario;

    public function __construct(array $campos)
    {
        $this->tabla = 'usuarios';
        $this->id = $campos['id'];
        $this->usuario = $campos['usuario'];
    }

    public function es_admin(): bool
    {
        return $this->usuario == 'admin';
    }

    public static function esta_logueado(): bool
    {
        return isset($_SESSION['login']);
    }

    public static function logueado(): ?static
    {
        return isset($_SESSION['login']) ? unserialize($_SESSION['login']) : null;
    }

    public static function comprobar($login, $password, ?PDO $pdo = null)
    {
        $pdo = $pdo ?? conectar();

        $sent = $pdo->prepare('SELECT *
                                 FROM usuarios
                                WHERE usuario = :login');
        $sent->execute([':login' => $login]);
        $fila = $sent->fetch(PDO::FETCH_ASSOC);

        if ($fila === false) {
            return false;
        }

        return password_verify($password, $fila['password']) ? new static($fila) : false;
    }

    public static function comprobarRegistro($username, $password, ?PDO $pdo = null)
    {
        $pdo = $pdo ?? conectar();
        $sent = $pdo->prepare('SELECT * FROM usuarios WHERE usuario = :username');
        $sent->execute([':username' => $username]);
        $fila = $sent->fetch(PDO::FETCH_ASSOC);

        if ($fila === false) {
            $sent = $pdo->prepare("INSERT INTO usuarios (usuario, password)
                                    VALUES (:username, crypt(:password, gen_salt('bf', 10)))");
            $sent->execute([':username' => $username, ':password' => $password]);

            $campos['usuario'] = $username;

            $usuario = new Usuario($campos);

            return $usuario;

        }
    }
}