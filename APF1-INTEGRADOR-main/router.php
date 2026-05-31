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
$file = __DIR__ . '/public' . $path;
if (file_exists($file) && is_file($file)) {
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    $mimeTypes = [
        'css' => 'text/css',
        'js'  => 'application/javascript',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg'=> 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml'
    ];
    if (array_key_exists($ext, $mimeTypes)) {
        header("Content-Type: " . $mimeTypes[$ext]);
    }
    readfile($file);
    return;
}

// Emular el comportamiento de mod_rewrite de Apache (.htaccess)
$_GET['url'] = ltrim($path, '/');
require __DIR__ . '/public/index.php';
