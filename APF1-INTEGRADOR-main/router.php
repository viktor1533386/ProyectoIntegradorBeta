<?php
// router.php para el servidor PHP embebido (utilizado en Railway u otros entornos)
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Si se solicita la raíz, redirigir al index
if ($path === '/') {
    $_GET['url'] = '';
    require __DIR__ . '/public/index.php';
    return;
}

// Si el archivo existe físicamente en el directorio public (ej. CSS, JS, imágenes), servirlo tal cual
if (file_exists(__DIR__ . '/public' . $path) && is_file(__DIR__ . '/public' . $path)) {
    return false;
}

// Emular el comportamiento de mod_rewrite de Apache (.htaccess)
$_GET['url'] = ltrim($path, '/');
require __DIR__ . '/public/index.php';
