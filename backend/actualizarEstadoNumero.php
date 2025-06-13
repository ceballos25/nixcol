<?php
include_once '../config/config.php';
include_once 'numeroDisponibleModel.php';

$numero = isset($_POST['numero']) ? $_POST['numero'] : '';
$nuevoEstado = isset($_POST['estado']) ? $_POST['estado'] : '';

$numeroDisponibleModel = new NumeroDisponibleModel($db);
$numeroDisponibleModel->actualizarEstadoPorNumero($numero, $nuevoEstado);

header('Content-Type: application/json');
echo json_encode(['success' => true]);
?>