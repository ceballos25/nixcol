<?php

// --- Configuración del Entorno ---
// Define el entorno de la aplicación. Puede ser 'development' o 'production'.
// Para cambiar de entorno, solo modifica esta constante.
defined('APP_ENV') or define('APP_ENV', 'development'); // O 'production' en tu servidor real

// Definir rutas absolutas del sistema de archivos para inclusión de archivos PHP
// DOCUMENT_ROOT suele ser la raíz del servidor web (ej. /var/www/html), no necesariamente la raíz de tu proyecto.
// Asegúrate de que '/edts-v4' es el subdirectorio de tu aplicación dentro de DOCUMENT_ROOT.
defined('DOCUMENT_ROOT') or define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);

// Define la ruta base para inclusiones de archivos PHP.
// Es la ruta física en el servidor a la carpeta raíz de tu aplicación.
defined('APP_ROOT_PATH') or define('APP_ROOT_PATH', DOCUMENT_ROOT . '/');

// Define la ruta para archivos PHP de inclusión que no son clases principales.
defined('INCLUDE_PATH') or define('INCLUDE_PATH', APP_ROOT_PATH . 'includes/');

// --- Definición de URL Base y Rutas Públicas ---
$base_url = '';
$public_assets_subdir = ''; // Inicializamos la variable

if (APP_ENV === 'development') {
    $base_url = 'http://nixcol.test/'; // La URL base del servidor web local
    $public_assets_subdir = ''; // El subdirectorio de tu aplicación para assets
    ini_set('display_errors', 1); // Mostrar errores en desarrollo
    error_reporting(E_ALL);
} else { // production
    $base_url = 'https://nixcol.com/'; // La URL base de tu dominio en producción
    $public_assets_subdir = ''; // En producción, los assets suelen estar en la raíz del dominio
    ini_set('display_errors', 0); // No mostrar errores en producción
    error_reporting(0); // Desactivar reportes de errores en producción
}

defined('BASE_URL') or define('BASE_URL', $base_url);

// Rutas para archivos públicos (CSS, JS, imágenes)
// Ahora, la lógica es consistente: BASE_URL + public_assets_subdir + /assets/...
defined('IMG_PATH') or define('IMG_PATH', BASE_URL . $public_assets_subdir . '/assets/images/');
defined('CSS_PATH') or define('CSS_PATH', BASE_URL . $public_assets_subdir . '/assets/css/');
defined('JS_PATH') or define('JS_PATH', BASE_URL . $public_assets_subdir . '/assets/js/');
defined('LIBS_PATH') or define('LIBS_PATH', BASE_URL . $public_assets_subdir . '/assets/libs/');
defined('PAGES_PATH') or define('PAGES_PATH', BASE_URL . $public_assets_subdir . '/pages/');
defined('BACK_PATH') or define('BACK_PATH', BASE_URL . $public_assets_subdir . '/backend/');

// --- Configuración Global ---
// Configurar la zona horaria a 'America/Bogota'
date_default_timezone_set('America/Bogota');

// --- Inclusión de Clases y Librerías ---
// Incluir el autoloader de Composer primero para que las clases de Composer estén disponibles.
require_once APP_ROOT_PATH . 'vendor/autoload.php';

// Incluir la clase de conexión a la base de datos
require_once APP_ROOT_PATH . 'config/database.php';

// Incluir las clases de consulta y paginación
require_once APP_ROOT_PATH . 'backend/queryObject/objectInsert.php';
require_once APP_ROOT_PATH . 'backend/queryObject/objectSelect.php';
require_once APP_ROOT_PATH . 'backend/queryObject/objectUpdate.php';
require_once APP_ROOT_PATH . 'backend/queryObject/objectDelete.php';
require_once APP_ROOT_PATH . 'backend/pagination.php';


// --- Conexión a la Base de Datos ---
// Crear una instancia de la clase Database y obtener la conexión.
try {
    $database = new Database();
    $db = $database->getConnection();
} catch (Exception $e) {
    // En producción, no muestres el error al usuario por seguridad.
    // En su lugar, registra el error y muestra un mensaje amigable.
    if (APP_ENV === 'production') {
        // Aquí podrías loguear el error en un archivo (ej. error_log($e->getMessage());)
        http_response_code(500); // Código de error interno del servidor
        die('Lo sentimos, estamos experimentando problemas técnicos. Por favor, inténtalo de nuevo más tarde.');
    } else {
        // En desarrollo, puedes mostrar el error completo para depuración.
        die('Error al conectar a la base de datos: ' . $e->getMessage());
    }
}

?>