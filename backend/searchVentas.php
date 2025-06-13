<?php
include_once '../config/config.php';
include_once '../backend/ventaModel.php';

// Parámetros de búsqueda y paginación
$search = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 50;
$offset = ($page - 1) * $limit;

// Crear una instancia de la clase VentaModel
$ventaModel = new VentaModel($db);

// Buscar ventas
$ventas = $ventaModel->buscarVentas($search, $limit, $offset);

// Contar el total de ventas
$totalRecords = $ventaModel->contarVentas($search);
$totalPages = ceil($totalRecords / $limit);

// Crear una instancia de la clase Paginator
$paginator = new Paginator($totalRecords, $limit, $page);

// Devolver los resultados en formato JSON
$response = [
    'ventas' => $ventas,
    'pagination' => $paginator->createLinks()
];

header('Content-Type: application/json');
echo json_encode($response);
?>