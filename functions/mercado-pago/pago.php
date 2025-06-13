<?php

class Pago {
    private $accessToken;

    public function __construct($accessToken) {
        $this->accessToken = $accessToken;
    }

    public function verificarPago($externalReference) {
        $url = "https://api.mercadopago.com/v1/payments/search?external_reference=$externalReference";
        
        // Inicialización de cURL
        $ch = curl_init();

        // Configuración de las opciones de cURL de manera más eficiente
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->accessToken,
            ]
        ]);

        // Ejecutar la petición
        $response = curl_exec($ch);

        // Manejar errores en la solicitud
        if (curl_errno($ch)) {
            $this->logError('Error en cURL: ' . curl_error($ch));
            curl_close($ch);
            return null;
        }

        // Cerrar la conexión cURL
        curl_close($ch);

        // Decodificar la respuesta JSON
        $responseData = json_decode($response, true);

        // Validar la respuesta
        if (!isset($responseData['results'][0])) {
            $this->logError("No se encontró el pago para la referencia externa: $externalReference");
            return null;
        }

        // Retornar si el pago fue aprobado
        $payment = $responseData['results'][0];
        return $payment['status'] === 'approved' ? $payment : null;
    }

    private function logError($message) {
        // Registra el error en un archivo de log
        error_log($message, 3, 'error_log.txt');
    }
}
