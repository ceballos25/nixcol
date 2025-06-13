<?php
// Incluir la clase de conexión
include_once '/home/eldiama1/nixcol.com/config/config.php';

// Definir la ruta del archivo de log
$logFile = 'error_log.txt';
// Establecer la zona horaria de Colombia
date_default_timezone_set('America/Bogota');

// Función para escribir en el log
function writeLog($message, $logFile) {
    $timestamp = date("Y-m-d H:i:s");
    $logMessage = "[{$timestamp}] {$message}\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

try {
    // Conexión a la base de datos
    $database = new Database();
    $db = $database->getConnection();

    // Verificar la conexión a la base de datos
    if (!$db) {
        throw new Exception("Error al conectar a la base de datos.");
    }

    // Consulta para obtener todos los registros de la tabla respaldo
    $sql = "SELECT id_transaccion FROM respaldo";
    $stmt = $db->prepare($sql);
    $stmt->execute();

    // Registrar el inicio del proceso
    writeLog("Inicia la tarea Cron.", $logFile);

    // Recorrer los resultados y hacer la solicitud GET para cada registro
    while ($row = $stmt->fetch()) {
        $externalReference = $row['id_transaccion'];


        // Asegurarse de que external_reference es válido
        if ($externalReference !== false && !empty($externalReference) && preg_match("/^[a-zA-Z0-9_-]+$/", $externalReference)) {
            // URL de venta-exitosa.php con el parámetro external_reference
            $url = "https://nixcol.com/functions/mercado-pago/venta-exitosa.php?external_reference=" . urlencode($externalReference)."&vendedor=VF";

            // Realizar la solicitud GET usando cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

            $response = curl_exec($ch);

            $endTime = microtime(true); // Tiempo de finalización de la solicitud

            // Manejo de errores de cURL
            if (curl_errno($ch)) {
                writeLog("Error cURL para id_transaccion {$externalReference}: " . curl_error($ch), $logFile);
            } else {
                writeLog("Solicitud GET exitosa para id_transaccion: {$externalReference}", $logFile);
            }

            curl_close($ch);
        } else {
            writeLog("Referencia externa no válida: {$row['id_transaccion']}", $logFile);
        }
    }

    // Registrar el inicio del proceso
    writeLog("Proceso finalizado correctamente.", $logFile);

} catch (PDOException $e) {
    // Si ocurre un error de PDO, lo registramos en el log
    writeLog("Error de PDO: " . $e->getMessage(), $logFile);
} catch (Exception $e) {
    // Si ocurre un error general, lo registramos en el log
    writeLog("ERROR: " . $e->getMessage(), $logFile);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forzar Ventas</title>
</head>
<body>

    <script>
        alert("Ventas forzadas correctamente.");
    </script>

</body>
</html>