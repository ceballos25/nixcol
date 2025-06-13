<?php

require_once '../../config/config.php';
include_once '../../backend/ventaModel.php';
// Incluir la clase Pago
require_once 'pago.php'; // Asegúrate de que la ruta sea correcta

// Función para escribir logs
function escribirLog($mensaje, $nivel = 'INFO') {
    $fecha = date('Y-m-d H:i:s');
    $logMessage = "[{$fecha}] [{$nivel}] {$mensaje}\n";
    file_put_contents('error_log.log', $logMessage, FILE_APPEND);
}

// Verificar si el pago está completado
function verificarPagoMercadoPago($externalReference, $db) {
    try {
        $pago = new Pago("APP_USR-934751295014170-061011-acdd353c93695fad69057dc4a7f8ef96-516647427");
        $payment = $pago->verificarPago($externalReference);

        if ($payment) {
            $status = $payment['status'];

            // Log para ver el estado de la transacción
            escribirLog("Estado de la transacción {$externalReference}: {$status}");

            // Responder con "Sí" o "No"
            if ($status == 'approved') {
                // Consulta para obtener la transacción desde la base de datos
                $querySelect = new QuerySelect();
                $query = $querySelect->select('*')
                    ->from('respaldo')
                    ->where('id_transaccion = :id_transaccion')
                    ->getQuery();

                $stmt = $db->prepare($query);
                $stmt->bindValue(':id_transaccion', $externalReference);
                $stmt->execute();

                // Verificar si se encontraron resultados
                if ($stmt->rowCount() > 0) {
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    // Crear el array $data con la estructura solicitada
                    $data = [];

                    // Cliente
                    $data['cliente'] = [
                        'nombre' => $row['cliente'] ?? '',
                        'celular' => $row['celular'] ?? '',
                        'email' => $row['correo'] ?? '',
                        'departamento' => $row['departamento'] ?? '',
                        'ciudad' => $row['ciudad'] ?? ''
                    ];

                    // Otros datos de la venta
                    $data['total_numeros'] = $row['oportunidades'] ?? 0;
                    $data['total_pago'] = $row['pago'] ?? 0;
                    $data['id_transaccion'] = $row['id_transaccion'] ?? '';
                    $data['tipo'] = 'PW';  // Valor fijo o puede ser dinámico según se necesite

                    // Determinar el valor de vendedor
                    $data['vendedor'] = isset($_GET['vendedor']) ? 'VF' : 'PW';

                    // Log para verificar los datos
                    escribirLog("Datos procesados para la venta con ID de transacción: {$data['id_transaccion']}", 'INFO');

                    // Crear la venta
                    $ventaModel = new VentaModel($db);
                    $resultHtml = $ventaModel->procesarVenta($data); // Procesar la venta
                    echo $resultHtml;

                    if ($resultHtml) {
                        $queryDelete = new QueryDelete();
                        $queryDelete = $queryDelete->from('respaldo')
                            ->where('id_transaccion = :id_transaccion')
                            ->getQuery();

                        $stmt = $db->prepare($queryDelete);
                        $stmt->bindValue(':id_transaccion', $externalReference);
                        $stmt->execute();

                        if ($queryDelete) {
                            escribirLog("Ventra procesado, registro {$externalReference} eliminado " . 'INFO');
                        }
                    }

                } else {
                    // Si no se encontró el registro en la base de datos
                    escribirLog("No se encontró la transacción en la base de datos con el id_transaccion: " . $externalReference, 'WARNING');
                    // Realiza una redirección a otra página de tu sitio web
                    header("Location: error.php"); // Aquí colocas la URL de la página a la que deseas redirigir
                    exit; // Es una buena práctica usar exit después de redirigir
                }
            } else {
                // Si el pago no está aprobado
                escribirLog("El pago no ha sido aprobado para la transacción ID: {$externalReference}", 'WARNING');
                    // Realiza una redirección a otra página de tu sitio web
                    header("Location: error.php"); // Aquí colocas la URL de la página a la que deseas redirigir
                    exit; // Es una buena práctica usar exit después de redirigir
            }
        } else {
            escribirLog("No se encontró la transacción en Mercado Pago para el id_transaccion: {$externalReference}", 'ERROR');
                    // Realiza una redirección a otra página de tu sitio web
                    header("Location: error.php"); // Aquí colocas la URL de la página a la que deseas redirigir
                    exit; // Es una buena práctica usar exit después de redirigir
        }
    } catch (Exception $e) {
        escribirLog("Error general: " . $e->getMessage(), 'ERROR');
                    // Realiza una redirección a otra página de tu sitio web
                    header("Location: error.php"); // Aquí colocas la URL de la página a la que deseas redirigir
                    exit; // Es una buena práctica usar exit después de redirigir
    }
}

// Procesar la solicitud
$database = new Database();
$db = $database->getConnection();

if ($db) {
    $externalReference = isset($_GET['external_reference']) ? $_GET['external_reference'] : null;

    if ($externalReference) {
        // Log para verificar el ID de la transacción recibido
        escribirLog("Recibiendo solicitud para verificar el pago con ID: {$externalReference}");
        verificarPagoMercadoPago($externalReference, $db); // Pasar la conexión a la función
    } else {
        escribirLog("No se proporcionó el id_transaccion por GET.", 'WARNING');
                    // Realiza una redirección a otra página de tu sitio web
                    header("Location: error.php"); // Aquí colocas la URL de la página a la que deseas redirigir
                    exit; // Es una buena práctica usar exit después de redirigir
    }
} else {
    escribirLog("Error al conectar a la base de datos.", 'ERROR');
                    // Realiza una redirección a otra página de tu sitio web
                    header("Location: error.php"); // Aquí colocas la URL de la página a la que deseas redirigir
                    exit; // Es una buena práctica usar exit después de redirigir
}

escribirLog("Fin de ejecución del script.");
?>