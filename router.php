<?php
/**
 * Router for PHP built-in server (e.g. php -S localhost:9090 -t . router.php).
 * Mimics .htaccess: strip .php extension in URLs and route 404 to 404.php.
 */
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = __DIR__ . $uri;

// Serve existing files and directories (static assets, real .php files)
if ($uri !== '/' && file_exists($path)) {
    if (is_dir($path)) {
        $path = rtrim($path, '/') . '/index.php';
        if (file_exists($path)) {
            require $path;
            return true;
        }
    }
    return false; // Let built-in server serve the file
}

// Strip leading slash and match "clean" URLs (no extension)
$segment = ltrim($uri, '/');
if ($segment === '' || $segment === 'index' || $segment === 'index.php') {
    require __DIR__ . '/index.php';
    return true;
}

$phpFile = __DIR__ . '/' . $segment . '.php';
if (file_exists($phpFile)) {
    $_SERVER['SCRIPT_NAME'] = '/' . $segment . '.php';
    require $phpFile;
    return true;
}

// 404
require __DIR__ . '/404.php';
return true;
