<?php

declare(strict_types=1);

require_once "../vendor/autoload.php";

use Varvara\Framework\Controller\AuthController;
use Varvara\Framework\Controller\GenerateController;
use Varvara\Framework\Controller\IndexController;
use Varvara\Framework\Controller\LanguageController;
use Varvara\Framework\Controller\ParseController;
use Varvara\Framework\Controller\StatController;
use Varvara\Framework\Routing\RouteCollection;
use Varvara\Framework\Routing\RouteMatcher;
use Varvara\Framework\Routing\Route;
use Varvara\Framework\Controller\FilterController;
use Varvara\Framework\Controller\OrganizationController;

session_name("USER_AUTH");
session_start();


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$collection = new RouteCollection();


$collection->add(new Route('/', 'GET', IndexController::class, 'index'));
$collection->add(new Route('/parse', 'GET', ParseController::class, 'parse'));
$collection->add(new Route('/filter', 'GET', FilterController::class, 'filter'));
$collection->add(new Route('/generate', 'POST', GenerateController::class, 'generate'));
$collection->add(new Route('/upload', 'GET', ParseController::class, 'parse'));
$collection->add(new Route('/stat', 'GET', StatController::class, 'showStatPage'));
$collection->add(new Route('/count/{organizationId}/{field}','GET',StatController::class,'countByField'));

$collection->add(new Route('/organization', 'GET', OrganizationController::class, 'show'));
$collection->add(new Route('/organization/create-form', 'GET', OrganizationController::class, 'showCreateForm'));
$collection->add(new Route('/organization/create', 'POST', OrganizationController::class, 'create'));
$collection->add(new Route('/organization/delete-form', 'GET', OrganizationController::class, 'showDeleteForm'));
$collection->add(new Route('/organization/delete', 'POST', OrganizationController::class, 'delete'));
$collection->add(new Route('/organization/change-form', 'GET', OrganizationController::class, 'showChangeForm'));
$collection->add(new Route('/organization/change', 'POST', OrganizationController::class, 'change'));
$collection->add(new Route('/organization/change/company', 'POST', OrganizationController::class, 'changeCompany'));
$collection->add(new Route('/register', 'POST', AuthController::class, 'register'));
$collection->add(new Route('/login', 'POST', AuthController::class, 'login'));
$collection->add(new Route('/logout', 'POST', AuthController::class, 'logout'));
$collection->add(new Route('/set-language', 'GET', LanguageController::class, 'setLanguage'));



$routeMatcher = new RouteMatcher($collection);
try {
    $route = $routeMatcher->match(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    $classname = $route->getClass();
    $method = $route->getClassMethod();

    $controller = new $classname();

    $params = $route->params;

   if (!empty($params)) {
        $controller->$method(...array_values($params));
    } else {
        $quantity = $_POST['quantity'] ?? 0;
        $controller->$method((int)$quantity);
   }

} catch (Exception $e) {
    http_response_code(404);
    echo "404 Not Found: " . $e->getMessage();
    exit;
}
