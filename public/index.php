<?php

require_once '../vendor/autoload.php';

use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

$request = Request::createFromGlobals();
$session = new Session();
$session->start();
$request->setSession($session);

session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

$fileLocator = new FileLocator([__DIR__ . '/../config']);
$loader = new YamlFileLoader($fileLocator);
$routes = $loader->load('routes.yaml');

$_SESSION['ini_array'] = parse_ini_file('../config/parameters.ini');

$context = new RequestContext('/');

$matcher = new UrlMatcher($routes, $context);
try {
    $route = $matcher->matchRequest($request);
    list($controllerName, $actionName) = explode("::", $route['_controller']);

    $controller = new $controllerName($request);
    $response = $controller->$actionName($route);

    if (!$response instanceof Response) {
        $response = new Response('Ошибка: неверный ответ от контролера', 500);
    }
} catch (ResourceNotFoundException $exception) {
    $response = new Response('Страница не найдена', 404);
} catch (Exception $exception) {
    $response = new Response('Ошибка: ' . $exception->getMessage(), 500);
}
$response->send();
