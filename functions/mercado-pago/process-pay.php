<?php

include_once '../../config/config.php';

use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

function validarYLimpiar($datos, $tipo) {
    if (!isset($datos)) {
        return false; // Retorna false si los datos no están definidos
    }
    switch ($tipo) {
        case 'email':
            return filter_var($datos, FILTER_VALIDATE_EMAIL); // Valida y limpia correos electrónicos
        case 'int':
            return filter_var($datos, FILTER_VALIDATE_INT); // Valida y limpia enteros
        case 'string':
            return htmlspecialchars($datos, ENT_QUOTES, 'UTF-8'); // Usa htmlspecialchars para seguridad
        default:
            return htmlspecialchars($datos, ENT_QUOTES, 'UTF-8'); // Usa htmlspecialchars por default.
    }
}

// Recolección y validación de datos del formulario
$celular = validarYLimpiar($_POST['celular'], 'string');
$nombre = validarYLimpiar($_POST['nombre'], 'string');
$correo = validarYLimpiar($_POST['email'], 'email');
$departamento = validarYLimpiar($_POST['departamento'], 'string');
$ciudad = validarYLimpiar($_POST['ciudad'], 'string');
$oportunidades = validarYLimpiar($_POST['total_numeros'], 'int');
$total_pago = validarYLimpiar($_POST['total_pago'], 'string'); // Limpiar total_pago como cadena

// Limpiar total_pago de caracteres no numéricos y convertir a entero
$total_pago = preg_replace('/\D/', '', $total_pago);

// Validación de datos requeridos
if (!$celular || !$nombre || !$correo || !$departamento || !$ciudad || !$oportunidades || empty($total_pago)) {
    die('Faltan datos requeridos o los datos no son válidos.'); // Detiene la ejecución si faltan datos
}

// Validación y extracción de los últimos 4 dígitos del celular
if (strlen($celular) < 4) {
    die('El número de celular no es válido.'); // Detiene la ejecución si el celular es inválido
}

$ultimoCuatroDigitos = substr($celular, -4);

// Generación de referencia externa única
$externalReference = bin2hex(random_bytes(7)) . $ultimoCuatroDigitos;

// Incluir el modelo de la base de datos
include_once '../../backend/ventaModel.php';

// Conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

if ($db) {
    // Crear una instancia del modelo de ventas
    $ventaModel = new VentaModel($db);

    // Guardar los datos de la venta en la base de datos
    $resultado = $ventaModel->guardarRespaldo(
        $nombre,
        $celular,
        $correo,
        $departamento,
        $ciudad,
        $oportunidades,
        $total_pago,
        $externalReference
    );

    if (!$resultado) {
        die('Error al guardar la transacción. Por favor, inténtelo de nuevo.'); // Detiene la ejecución si falla el guardado
    }
} else {
    die('Error al conectar a la base de datos.'); // Detiene la ejecución si falla la conexión
}

// Configuración de las credenciales de Mercado Pago
MercadoPagoConfig::setAccessToken('APP_USR-934751295014170-061011-acdd353c93695fad69057dc4a7f8ef96-516647427'); //PRODUCCION NIXCOL


// Configuración de las URLs de retorno para Mercado Pago
$backUrls = [
    "success" => "https://nixcol.com/functions/mercado-pago/venta-exitosa.php?external_reference=".urlencode($externalReference),
    "failure" => "https://nixcol.com/functions/mercado-pago/error.php",
    "pending" => "https://nixcol.com/functions/mercado-pago/pending.php"
];

// Creación de la preferencia de pago en Mercado Pago
$client = new PreferenceClient();
$preference = $client->create([
    "items" => [[
        "id" => "NIXCOL-001",
        "title" => "Entradas Nixcol",
        "description" => "Entradas Nixcol",
        "category_id" => "Entradas",
        "quantity" => 1,
        "currency_id" => "COP",
        "unit_price" => (int) $total_pago // Convertir total_pago a entero para Mercado Pago
    ]],
    "payer" => [
        "name" => $nombre,
        "surname" => "",
        "email" => $correo,
        "phone" => [
            "area_code" => "57",
            "number" => $celular
        ]
    ],
    "notification_url" => "https://nixcol.com/functions/mercado-pago/notificacion.php",
    "back_urls" => $backUrls,
    "auto_return" => "approved",
    "external_reference" => $externalReference,
    "statement_descriptor" => "Nixcol Entradas",
    "binary_mode" => false
]);

// Redirección a la página de pago de Mercado Pago
header("Location: " . $preference->init_point);
exit;
?>