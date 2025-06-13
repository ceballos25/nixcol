<?php
include_once '../config/config.php';
include_once 'numeroDisponibleModel.php';

// Parámetros de búsqueda, estado y paginación
$search = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 50;
$offset = ($page - 1) * $limit;

// Crear una instancia de la clase NumeroDisponibleModel
$numeroDisponibleModel = new NumeroDisponibleModel($db);

// Buscar números disponibles
$numerosDisponibles = $numeroDisponibleModel->buscarNumerosDisponibles($search, $limit, $offset);

// Contar el total de números disponibles
$totalRecords = $numeroDisponibleModel->contarNumerosDisponibles($search);
$totalPages = ceil($totalRecords / $limit);

// Crear una instancia de la clase Paginator
$paginator = new Paginator($totalRecords, $limit, $page);

// Devolver los resultados en formato JSON
$response = [
    'numerosDisponibles' => $numerosDisponibles,
    'pagination' => $paginator->createLinks()
];

header('Content-Type: application/json');
echo json_encode($response);
?>