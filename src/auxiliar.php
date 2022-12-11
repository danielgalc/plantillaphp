<?php 

function conectar()
{
    return new \PDO('pgsql:host=localhost, dbname=meneame', 'meneame', 'meneame');
}

function hh($x)
{
    return htmlspecialchars($x ?? '', ENT_QUOTES | ENT_SUBSTITUTE);

}

function dinero($s)
{
    return number_format($s, 2, ',', ' ') . ' €';
}

function obtener_get($par)
{
    return obtener_parametro($par, $_GET);
}

function obtener_post($par)
{
    return obtener_parametro($par, $_POST);
}

function obtener_parametro($par, $array)
{
    return isset($array[$par]) ? trim($array[$par]) : null;
}

// Funcion Carrito/Favorito

function favorito()
{
    if (!isset($_SESSION['favorito'])) {
        $_SESSION['favorito'] = serialize(new \App\Generico\Favorito());
    }

    return $_SESSION['favorito'];
}

function favorito_vacio()
{
    $favorito = unserialize(favorito());

    return $favorito->vacio();
}

function volver()
{
    header('Location: /index.php');
}

function volver_admin()
{
    header("Location: /admin/");
}

function redirigir_login()
{
    header('Location: /login.php');
}

function redirigir_dashboard()
{
    header('Location: /dashboard.php');
}