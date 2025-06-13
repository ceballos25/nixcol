<?php
include_once '../config/config.php';
include_once 'respaldoModel.php';

// Parámetros de búsqueda y paginación
$search = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Crear una instancia de la clase RespaldoModel
$respaldoModel = new RespaldoModel($db);

// Buscar registros de respaldo
$respaldo = $respaldoModel->buscarRespaldo($search, $limit, $offset);

// Contar el total de registros de respaldo
$totalRecords = $respaldoModel->contarRespaldo($search);
$totalPages = ceil($totalRecords / $limit);

// Crear una instancia de la clase Paginator
$paginator = new Paginator($totalRecords, $limit, $page);

// Devolver los resultados en formato JSON
$response = [
    'respaldo' => $respaldo,
    'pagination' => $paginator->createLinks()
];

header('Content-Type: application/json');
echo json_encode($response);

// Cerrar la conexión
$db = null;
?>