<?php
// ============================================================
//  public/index.php — Front Router
// ============================================================

require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/app/helpers/Session.php';
require_once dirname(__DIR__) . '/app/helpers/Validator.php';
require_once dirname(__DIR__) . '/app/helpers/Site.php';

Session::start();

// Parse request
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base   = parse_url(APP_URL, PHP_URL_PATH) ?: '';
$path   = '/' . ltrim(substr($uri, strlen($base)), '/');
$method = $_SERVER['REQUEST_METHOD'];

// ── Routing table ──────────────────────────────────────────
$routes = [
    'GET'  => [
        '/'                   => ['PageController',   'home'],
        '/services'           => ['PageController',   'services'],
        '/services/{slug}'    => ['PageController',   'serviceDetail'],
        '/portfolio'          => ['PageController',   'portfolio'],
        '/about'              => ['PageController',   'about'],
        '/contact'            => ['PageController',   'contact'],

        '/login'              => ['AuthController',   'showLogin'],
        '/register'           => ['AuthController',   'showRegister'],
        '/logout'             => ['AuthController',   'logout'],
        '/forgot-password'    => ['AuthController',   'showForgotPassword'],

        '/dashboard'          => ['ClientController', 'dashboard'],
        '/dashboard/orders'   => ['ClientController', 'orders'],
        '/dashboard/profile'  => ['ClientController', 'profile'],

        '/order'              => ['OrderController',  'show'],

        '/admin'              => ['AdminController',  'dashboard'],
        '/admin/orders'       => ['AdminController',  'orders'],
        '/admin/orders/{id}/edit' => ['AdminController','editOrder'],
        '/admin/users'        => ['AdminController',  'users'],
        '/admin/users/{id}/edit' => ['AdminController','editUser'],
        '/admin/services'     => ['AdminController',  'services'],
        '/admin/portfolio'    => ['AdminController',  'portfolio'],

        '/designer/dashboard' => ['DesignerController','dashboard'],
        '/designer/orders/{id}' => ['DesignerController','viewOrder'],
        '/designer/pending'   => ['DesignerController','pendingReview'],

        '/manager/dashboard'  => ['ManagerController', 'dashboard'],
        '/manager/orders'     => ['ManagerController', 'orders'],
        '/manager/orders/{id}/edit' => ['ManagerController','editOrder'],
        '/manager/team'       => ['ManagerController', 'teamWorkload'],
        '/manager/analytics'  => ['ManagerController', 'analytics'],

        '/payment'            => ['PaymentController','show'],
        '/payment/finish'     => ['PaymentController','finish'],
        '/payment/error'      => ['PaymentController','error'],
        '/payment/pending'    => ['PaymentController','pending'],
    ],
    'POST' => [
        '/login'              => ['AuthController',   'login'],
        '/register'           => ['AuthController',   'register'],
        '/forgot-password'    => ['AuthController',   'forgotPassword'],
        '/contact'            => ['PageController',   'sendContact'],
        '/order'              => ['OrderController',  'create'],
        
        '/admin/orders/{id}/status' => ['AdminController','updateOrderStatus'],
        '/admin/orders/{id}/update' => ['AdminController','updateOrder'],
        '/admin/users/{id}/update'  => ['AdminController','updateUser'],
        '/admin/users/{id}/toggle'  => ['AdminController','toggleUserStatus'],

        '/designer/orders/{id}/status' => ['DesignerController','updateOrderStatus'],
        '/designer/orders/{id}/upload' => ['DesignerController','uploadAsset'],

        '/manager/orders/{id}/update'  => ['ManagerController','updateOrder'],
        '/manager/orders/{id}/approve' => ['ManagerController','approveOrder'],

        '/payment/process'    => ['PaymentController','process'],
        '/payment/webhook/midtrans' => ['PaymentController','webhookMidtrans'],
        '/payment/webhook/xendit'   => ['PaymentController','webhookXendit'],
        '/dashboard/profile'  => ['ClientController', 'updateProfile'],
    ],
];

// ── Route matcher ──────────────────────────────────────────
function matchRoute(array $routeMap, string $path, string $method): ?array
{
    $map = $routeMap[$method] ?? [];

    // Exact match
    if (isset($map[$path])) {
        return ['handler' => $map[$path], 'params' => []];
    }

    // Pattern match {slug} / {id}
    foreach ($map as $pattern => $handler) {
        $regex = '#^' . preg_replace('/\{[^}]+\}/', '([^/]+)', $pattern) . '$#';
        if (preg_match($regex, $path, $matches)) {
            array_shift($matches);
            return ['handler' => $handler, 'params' => $matches];
        }
    }

    return null;
}

$match = matchRoute($routes, $path, $method);

if (!$match) {
    http_response_code(404);
    $title = 'Halaman Tidak Ditemukan';
    require VIEW_PATH . '/partials/404.php';
    exit;
}

// ── Load & dispatch controller ────────────────────────────
[$controllerClass, $action] = $match['handler'];
$params = $match['params'];

$controllerFile = ROOT_PATH . '/app/controllers/' . $controllerClass . '.php';

if (!file_exists($controllerFile)) {
    http_response_code(500);
    die('Controller tidak ditemukan: ' . $controllerClass);
}

require_once $controllerFile;

$controller = new $controllerClass();

if (!method_exists($controller, $action)) {
    http_response_code(500);
    die('Method tidak ditemukan: ' . $action);
}

call_user_func_array([$controller, $action], $params);
