<?php
    require __DIR__ . "/../app/Core/AutoLoader.php";
    require __DIR__ . '/../config/database.php';

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $base = '/santri-belajar/public';
    if (str_starts_with($uri, $base)) {
        $uri = substr($uri, strlen($base));
    }

    $uri = $uri == '' ? '/' : $uri; 
    $routes = [
        '/'            => ['App\Controllers\HomeController',   'index'],
        '/about'       => ['App\Controllers\HomeController',   'about'],
        '/faq'         => ['App\Controllers\HomeController',   'faq'],
  
        '/login'       => ['App\Controllers\AuthController',   'showLogin'],
        '/register'    => ['App\Controllers\AuthController',   'showRegister'],
        '/logout'      => ['App\Controllers\AuthController',   'logout'],
  
        '/poli/{id}'   => ['App\Controllers\PoliController',   'detail'],
        '/dokter/{id}' => ['App\Controllers\DokterController', 'detail'],
    ];

    $matched = null;
    $params = [];

    foreach ($routes as $pattern => $handler) {
       $regex = preg_replace('#\{([a-zA-Z0-9_]+)\}#', '([^/]+)', $pattern);

       if (preg_match("#^{$regex}$#", $uri, $matches)) {
        $matched = $handler;
        array_shift($matches);
        $params = $matches;
        break;
        }
    }

    if ($matched === null) {
        http_response_code(404);
        echo "404 Not Found";
        exit;
    }

    [$controllerClass, $method] = $matched;

    $controller = new $controllerClass();
    call_user_func_array([$controller, $method], $params)
?>