<?php
include_once '../config/config.php';
include_once 'numeroVendidoModel.php';

// Parámetros de búsqueda y paginación
$search = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Crear una instancia de la clase NumeroVendidoModel
$numeroVendidoModel = new NumeroVendidoModel($db);

// Buscar números vendidos
$numerosVendidos = $numeroVendidoModel->buscarNumerosVendidos($search, $limit, $offset);

// Contar el total de números vendidos
$totalRecords = $numeroVendidoModel->contarNumerosVendidos($search);
$totalPages = ceil($totalRecords / $limit);

// Crear una instancia de la clase Paginator
$paginator = new Paginator($totalRecords, $limit, $page);

// Devolver los resultados en formato JSON
$response = [
    'numerosVendidos' => $numerosVendidos,
    'pagination' => $paginator->createLinks()
];

header('Content-Type: application/json');
echo json_encode($response);
?>