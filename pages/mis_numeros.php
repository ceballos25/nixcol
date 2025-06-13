<?php
include_once '../config/config.php';
include_once '../backend/ventaModel.php';
include_once '../backend/clienteModel.php';

// Verifica si se recibió el celular
if (isset($_POST['celular'])) {
    $celular = $_POST['celular'];

    // Validar que sea un número válido (por ejemplo, solo dígitos)
    if (!preg_match('/^\d{10}$/', $celular)) {
        die("El número de celular no es válido.");
    }

    // Crear una instancia de la clase VentaModel
    $clienteModel = new ClienteModel($db);
    $ventaModel = new VentaModel($db);

    // Obtener el nombre y correo del cliente basado en el celular
    $cliente = $clienteModel->obtenerClientePorCelular($celular);

    // Si el cliente existe, continuar
    if ($cliente) {
        $nombre = $cliente['nombre'];
        $correoCliente = $cliente['correo'];  // Obtener correo del cliente

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
    } else {
        // Si no se encuentra el cliente, mostrar un mensaje de error
        echo "Cliente no encontrado.";
    }
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
        <button onclick="window.location.href='../index.php';">Regresar</button>
    </div>
</body>
</html>
