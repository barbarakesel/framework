<?php

declare(strict_types=1);

require_once "../vendor/autoload.php";

use Varvara\Framework\App\Filter;
use Varvara\Framework\App\Generate;
use Varvara\Framework\App\Parse;
use Varvara\Framework\App\File;
use Varvara\Framework\Routing\RouteCollection;
use Varvara\Framework\Routing\RouteMatcher;
use Varvara\Framework\Routing\Route;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$collection = new RouteCollection();


$collection->add(new Route('/', 'GET', \Varvara\Framework\Controller\IndexController::class, 'index'));
$collection->add(new Route('/parse', 'GET', Parse::class, 'parse'));
$collection->add(new Route('/filter', 'GET', Filter::class, 'filter'));
$collection->add(new Route('/generate-form', 'GET', \Varvara\Framework\Controller\IndexController::class, 'showGenerateForm'));
$collection->add(new Route('/generate', 'POST', Generate::class, 'generate'));
$collection->add(new Route('/file', 'POST', File::class, 'file'));
$collection->add(new Route('/upload', 'GET', Parse::class, 'parse'));
//$collection->add(new Route('/count/{field}', 'GET', \Varvara\Framework\Controller\StatController::class, 'countByField'));
$collection->add(new Route('/stat', 'GET', \Varvara\Framework\Controller\StatController::class, 'showStatPage'));
$collection->add(new Route('/count/{organizationId}/{field}','GET',\Varvara\Framework\Controller\StatController::class,'countByField'));

$collection->add(new Route('/organization', 'GET', \Varvara\Framework\Controller\OrganizationController::class, 'show'));
$collection->add(new Route('/organization/create-form', 'GET', \Varvara\Framework\Controller\OrganizationController::class, 'showCreateForm'));
$collection->add(new Route('/organization/create', 'POST', \Varvara\Framework\Controller\OrganizationController::class, 'create'));
$collection->add(new Route('/organization/delete-form', 'GET', \Varvara\Framework\Controller\OrganizationController::class, 'showDeleteForm'));
$collection->add(new Route('/organization/delete', 'POST', \Varvara\Framework\Controller\OrganizationController::class, 'delete'));
$collection->add(new Route('/organization/change-form', 'GET', \Varvara\Framework\Controller\OrganizationController::class, 'showChangeForm'));
$collection->add(new Route('/organization/change', 'POST', \Varvara\Framework\Controller\OrganizationController::class, 'change'));
$collection->add(new Route('/organization/change/company', 'POST', \Varvara\Framework\Controller\OrganizationController::class, 'changeCompany'));

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
