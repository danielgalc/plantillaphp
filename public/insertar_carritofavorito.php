<?php
session_start();

require '../vendor/autoload.php';

try {
    $id = obtener_get('id');

    if ($id === null) {
        return volver();
    }

    $favorito = unserialize(favorito());
    $favorito->insertar($id);
    $_SESSION['favorito'] = serialize($favorito);
} catch (ValueError $e) {
    // TODO: mostrar mensaje de error en un Alert
}

volver();