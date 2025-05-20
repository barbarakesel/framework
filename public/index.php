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

//use Dotenv\Dotenv;

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



$routeMatcher = new RouteMatcher($collection);
try {
    $route = $routeMatcher->match($_SERVER['REQUEST_URI']);
} catch (Exception $e) {
    echo "error";
}

$classname = $route->getClass();
$method = $route->getClassMethod();

$controller = new $classname();
$quantity = $_POST['quantity'] ?? 0;

$controller->$method((int)$quantity);
