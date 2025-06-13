<?php
// // Habilitar la visualización de errores
// ini_set('display_errors', 1);

// // Informar todos los errores
// error_reporting(E_ALL);

include_once '../config/config.php';
include_once '../config/database.php';
include_once '../backend/ventaModel.php';

$database = new Database();
$db = $database->getConnection();

// Depuración: Imprime los datos recibidos para verificar

// Recoger los datos del formulario (con manejo de errores)
$data = [];


// Recoger los datos del formulario (con manejo de errores)
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
    'tipo'           => $_POST['tipo'] ?? "VM", // Valor por defecto
    'vendedor'       => $_POST['vendedor'] ?? "VendedorDefault"
];

// Verificar el contenido de $data
// var_dump($data);
// exit;



// var_dump($data); // Depuración
// exit;

// Crear la venta
$ventaModel = new VentaModel($db);
$resultHtml = $ventaModel->procesarVenta($data);

echo $resultHtml; // Mostrar el resultado
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Botón Regresar</title>
    <style>
        button {
            background-color: rgb(0, 0, 0); /* Color verde */
            color: white; /* Texto blanco */
            border: none; /* Sin borde */
            padding: 10px 10px; /* Espaciado del botón */
            font-size: 14px; /* Tamaño del texto */
            cursor: pointer; /* Cambia el cursor al pasar sobre el botón */
            border-radius: 8px; /* Bordes redondeados */
            transition: background-color 0.3s ease; /* Transición suave al pasar el mouse */
        }

        button:hover {
            background-color: rgb(255, 255, 255); /* Cambia el color al pasar el mouse */
            color: rgb(0, 0, 0); /* Cambia el color al pasar el mouse */            
        }
    </style>
</head>
<body>
    <div style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.location.href='../pages/vender.php';">Regresar</button>
    </div>
</body>
</html>