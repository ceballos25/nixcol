<?php
include_once '../config/config.php';
include_once '../config/database.php';
include_once '../backend/ventaModel.php';

$database = new Database();
$db = $database->getConnection();

// Recoger datos del formulario
$numeroPremiado = $_POST['premiado'] ?? null;  // Capturamos el número premiado

$data = [
    'cliente' => [
        'nombre'       => $_POST['nombre'] ?? '',
        'celular'      => $_POST['celular'] ?? '',
        'email'        => $_POST['email'] ?? '',
        'departamento' => $_POST['departamento'] ?? '',
        'ciudad'       => $_POST['ciudad'] ?? ''
    ],
    'total_numeros'  => $_POST['total_numeros'] ?? 0,
    'total_pago'     => isset($_POST['total_pago']) ? preg_replace('/[^\d]/', '', $_POST['total_pago']) : 0,
    'id_transaccion' => $_POST['id_transaccion'] ?? '',
    'tipo'           => $_POST['tipo'] ?? "VM",
    'vendedor'       => $_POST['vendedor'] ?? "VendedorDefault"
];

// Crear y procesar la venta
$ventaModel = new VentaModel($db);

// Si hay número premiado, lo pasamos como segundo parámetro
$resultHtml = $numeroPremiado 
    ? $ventaModel->procesarVenta($data, $numeroPremiado)
    : $ventaModel->procesarVenta($data);

echo $resultHtml;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Botón Regresar</title>
    <style>
        button {
            background-color: rgb(0, 0, 0);
            color: white;
            border: none;
            padding: 10px 10px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: rgb(255, 255, 255);
            color: rgb(0, 0, 0);            
        }
    </style>
</head>
<body>
    <div style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.location.href='../pages/escoger.php';">Regresar</button>
    </div>
</body>
</html>