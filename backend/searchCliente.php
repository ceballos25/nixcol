<?php
include_once '../config/config.php';
include_once 'clienteModel.php';

$celular = isset($_POST['celular']) ? $_POST['celular'] : '';

if ($celular) {
    $clienteModel = new ClienteModel($db);
    $cliente = $clienteModel->obtenerClientePorCelular($celular);

    if ($cliente) {
        echo json_encode(['success' => true, 'cliente' => $cliente]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Cliente no encontrado']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Número de celular no proporcionado']);
}
?>