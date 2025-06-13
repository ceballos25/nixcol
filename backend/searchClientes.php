<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once '../config/config.php';
include_once 'clienteModel.php';

// Parámetros de búsqueda y paginación
$search = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Crear una instancia de la clase ClienteModel
$clienteModel = new ClienteModel($db);

// Buscar clientes
$clientes = $clienteModel->buscarClientes($search, $limit, $offset);

// Contar el total de clientes
$totalRecords = $clienteModel->contarClientes($search);
$totalPages = ceil($totalRecords / $limit);

// Crear una instancia de la clase Paginator
$paginator = new Paginator($totalRecords, $limit, $page);

// Devolver los resultados en formato JSON
$response = [
    'clientes' => $clientes,
    'pagination' => $paginator->createLinks()
];

header('Content-Type: application/json');
echo json_encode($response);

// Cerrar la conexión
$db = null;
?>