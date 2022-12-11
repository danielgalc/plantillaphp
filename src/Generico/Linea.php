<?php 

namespace App\Tablas;

use PDO;

class Noticia 
{
    public $id;
    public $titular;
    public $noticia_usuario;
    public $likes;
    public $created_at;

    public function __construct(array $campos)
    {
        $this->id = $campos['id'];
        $this->titular = $campos['titular'];
        $this->noticia_usuario = $campos['noticia_usuario'];
        $this->likes = $campos['likes'];
        $this->created_at = $campos['created_at'];
    }

    public static function existe(int $id, ?PDO $pdo = null): bool
    {
        return static::obtener($id) !== null;
    }

    public static function obtener(int $id, ?PDO $pdo = null): ?static
    {
        $pdo = $pdo ?? conectar();
        $sent = $pdo->prepare('SELECT *
                                 FROM noticias
                                WHERE id = :id');
        $sent->execute([':id' => $id]);
        $fila = $sent->fetch(PDO::FETCH_ASSOC);
        if ($fila === null){
            return null;
        }

        return new static($fila);
        return $fila ? new static($fila) : null;
    }

    public function getTitular()
    {
        return $this->titular;
    }

    public function getLikes()
    {
        return $this->likes;
    }

    public function getNoticiaUsuario(): int
    {
        return $this->noticia_usuario;
        
    }

    public function getFecha()
    {
        return $this->created_at;
    }
}