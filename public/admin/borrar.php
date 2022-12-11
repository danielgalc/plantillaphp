<?php
session_start();
require '../../vendor/autoload.php';

$id = obtener_post('id');

if (!isset($id)) {
    return volver();
}

$pdo = conectar();
$sent = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
$sent->execute([':id' => $id]);
$_SESSION['exito'] = 'Usuario eliminado correctamente';
return volver_admin();