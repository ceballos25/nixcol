<?php
include_once '../config/config.php';
include_once 'clienteModel.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$clienteModel = new ClienteModel($db);
$clienteModel->eliminarCliente($id);

header('Content-Type: application/json');
echo json_encode(['success' => true]);
?>