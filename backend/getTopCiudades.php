<?php
include_once '../config/config.php';
include_once DOCUMENT_ROOT . '/backend/queryDash.php';

// Crear una instancia de la clase DashboardQueries
$dashboardQueries = new DashboardQueries($db);

// Obtener las ciudades con más compras
$topCiudades = $dashboardQueries->getTopCiudades();

// Devolver los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($topCiudades);

?>