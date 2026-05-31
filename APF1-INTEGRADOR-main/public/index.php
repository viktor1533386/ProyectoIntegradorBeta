<?php
// ============================================================
//  PUBLIC/INDEX.PHP – Entry point del Framework MVC
//  Toda petición HTTP pasa por aquí gracias al .htaccess
// ============================================================

session_start();

// Habilitar reporte de errores temporalmente para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        http_response_code(200); // Enviar 200 para que Chrome no oculte el error
        echo "<div style='padding:20px;background:#f8d7da;color:#721c24;font-family:sans-serif;'>";
        echo "<h2>FATAL ERROR CAUGHT:</h2>";
        echo "<b>" . htmlspecialchars($error['message']) . "</b><br>";
        echo "File: " . $error['file'] . " (Line " . $error['line'] . ")";
        echo "</div>";
        // Relleno para asegurar que navegadores muestren el contenido
        echo str_repeat(' ', 1024);
    }
});

// Soporte para Nginx (Railway) donde mod_rewrite no inyecta $_GET['url']
if (!isset($_GET['url'])) {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    // Remover BASE_URL base si existe (ej. para subcarpetas en local)
    $script_dir = dirname($_SERVER['SCRIPT_NAME']); // ej. /APF1-INTEGRADOR/public
    if ($script_dir !== '/' && strpos($uri, $script_dir) === 0) {
        $uri = substr($uri, strlen($script_dir));
    }
    $_GET['url'] = ltrim($uri, '/');
}

// Cargar configuración
require_once dirname(__DIR__) . '/config/config.php';

// Cargar núcleo del framework
require_once APP_ROOT . '/core/Database.php';
require_once APP_ROOT . '/core/Model.php';
require_once APP_ROOT . '/core/Controller.php';
require_once APP_ROOT . '/core/Middleware.php';
require_once APP_ROOT . '/core/App.php';

// Iniciar el Router
$app = new App();
