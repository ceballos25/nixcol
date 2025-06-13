<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include_once '../config/config.php';
include_once 'clienteModel.php';

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$data = [
    'nombre' => $_POST['nombre'],
    'celular' => $_POST['celular'],
    'correo' => $_POST['correo'],
    // 'departamento' => $_POST['departamento'],
    // 'ciudad' => $_POST['ciudad']
];

$clienteModel = new ClienteModel($db);
$clienteModel->actualizarCliente($id, $data);

header('Content-Type: application/json');
echo json_encode(['success' => true]);
?>