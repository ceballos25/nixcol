<?php
include_once '../config/config.php';
include_once '../backend/ventaModel.php';

// Verifica si se recibió el celular
if (isset($_GET['celular'])) {
    $celular = $_GET['celular'];
    $nombre = $_GET['nombre'];
    $correoCliente = $_GET['correo'];  // Asegúrate de recibir el correo también

    // Crear una instancia de la clase VentaModel
    $ventaModel = new VentaModel($db);

    // Llamar a la función para obtener los números vendidos por celular
    $numerosVendidosPorCliente = $ventaModel->obtenerNumerosVendidosPorCliente($celular);

    // Generar la lista de números vendidos en formato HTML
    $numerosHtmlCliente = "";
    foreach ($numerosVendidosPorCliente as $numero) {
        $numerosHtmlCliente .= "<span class='number' style=''>" . htmlspecialchars($numero['numero']) . "</span>";
    }

    // Preparar los datos para mostrar
    $cantidadNumerosCliente = count($numerosVendidosPorCliente);

    // Cargar la plantilla HTML
    $templatePathCliente = '../backend/templeate/templeate.html'; // Ruta al archivo de la plantilla
    $htmlCliente = file_get_contents($templatePathCliente);

    // Reemplazar las variables en la plantilla
    $htmlCliente = str_replace(
        ['{clienteNombre}', '{cantidadNumeros}', '{numerosGenerados}'],
        [$nombre, $cantidadNumerosCliente, $numerosHtmlCliente],
        $htmlCliente
    );

    // Enviar el correo de confirmación
    $ventaModel->enviarCorreoConfirmacion($correoCliente, 'Recordatorio de Venta', $htmlCliente);  // Reutilizar el método para enviar el correo

    // Mostrar el HTML generado
    echo $htmlCliente;
}
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
        <button onclick="window.location.href='detalle-ventas.php';">Regresar</button>
    </div>
</body>
</html>
