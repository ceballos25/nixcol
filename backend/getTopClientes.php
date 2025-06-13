<?php
include_once '../config/config.php';
include_once DOCUMENT_ROOT . '/backend/queryDash.php';

// Crear una instancia de la clase DashboardQueries
$dashboardQueries = new DashboardQueries($db);

// Obtener los 10 clientes con más compras
$topClientes = $dashboardQueries->getTopClientes();

// Devolver los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($topClientes);
?>