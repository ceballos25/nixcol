<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
// Asegúrate de incluir los archivos necesarios
include_once '../config/config.php';
include_once '../backend/ventaModel.php';

// Verificar si se han recibido los parámetros
if (isset($_GET['id'], $_GET['celular'], $_GET['nombre'])) {
    // Asignar los valores de los parámetros a las variables
    $idVenta = $_GET['id']; // ID de la venta
    $celular = $_GET['celular']; // Celular del cliente
    $nombre = $_GET['nombre']; // Nombre del cliente
    $correo = $_GET['correo']; // Nombre del cliente, aquí debería estar el correo del cliente

    // Crear instancia del modelo
    $ventaModel = new VentaModel($db);

    // Obtener los números vendidos por esta venta
    $numerosVendidosPorVenta = $ventaModel->obtenerNumerosVendidosPorVenta($idVenta);

    // Verificar si se encontraron números vendidos
    if ($numerosVendidosPorVenta) {
        // Asignar los números vendidos a la variable $numeros
        $numeros = $numerosVendidosPorVenta;

        // Continuar con la lógica para actualizar los estados de los números
        $ventaModel->actualizarEstadoNumerosAnulacion($numeros, 'Disponible');
        $ventaModel->eliminarNumerosVendidos($idVenta);
        $ventaModel->eliminarVenta($idVenta);

        // Preparar los datos para la respuesta
        $data = [
            'cliente' => ['nombre' => $nombre],  // Usar el nombre recibido en la URL
            'total_numeros' => count($numeros),  // Contar los números anulados
        ];

        // Llamar a la función para preparar el HTML de respuesta
        $htmlRespuesta = $ventaModel->prepararHtmlRespuestaAnulacion($data, $numeros);

        // Llamar a la función para enviar el correo
        $ventaModel->enviarCorreoConfirmacion($correo, 'Anulacion venta: '. $idVenta, $htmlRespuesta);  // Asegúrate de pasar el correo real del cliente

        // Imprimir el HTML generado en la página (opcional)
        echo $htmlRespuesta;
    } else {
        // Si no se encontraron números vendidos, mostrar mensaje de error
        echo "No se encontraron números vendidos para esta venta.";
    }
} else {
    // Si faltan parámetros en la URL, mostrar mensaje de error
    echo "Faltan parámetros en la URL.";
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
