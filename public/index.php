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

$filter = new Filter();
$parse = new Parse();
$generate = new Generate();

$collection = new RouteCollection();

$route = new Route('/', 'GET', \Varvara\Framework\Controller\IndexController::class, 'index');
//$route = new Route('/parse', 'GET', Parse::class, 'parse');
//$route = new Route('/filter', 'GET', Filter::class, 'filter');


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


/*
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestUri = trim($requestUri, '/');

function showGenerateForm(): void
{
    echo "<div style='background: lightpink; color: white; padding: 20px;  height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; '>
          <form method='POST' action='/generate'>
                <input type='number' name='quantity' min='1' max='100' style = 'width: 250px; height: 50px; font-size: 20px; border-radius: 12px; background: white'>
                <button type='submit' style = 'width: 250px; height: 50px; font-size: 20px; border-radius: 12px; background: white'>Generate</button>
          </form>
          </div>";
}

if ($requestUri == 'filter') {
    $filter->filter();
} elseif ($requestUri == 'parse') {
    $parse->parse();
} elseif ($requestUri == 'generate') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $quantity = (int)$_POST['quantity'];
        $generate->generate($quantity);
    } else {
        showGenerateForm();
    }
} elseif ($requestUri == '') {
    echo "
<div style='background: lightpink; color: white; padding: 20px;  height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; '>
<h1> Welcome to main page </h1>
<div style='display:flex; justify-content:center; flex-direction:column; '>
            <a href = '/generate' style = 'padding: 20px; '><button style = 'width: 250px; height: 50px; font-size: 20px; border-radius: 12px; background: white'>Generate Data</button></a>
            <a href = '/parse' style = 'padding: 20px;'><button style = 'width: 250px; height: 50px; font-size: 20px; border-radius: 12px; background: white'>Parse Data</button></a>
            <a href = '/filter' style = 'padding: 20px;'><button style = 'width: 250px; height: 50px; font-size: 20px; border-radius: 12px; background: white'>Filter Data</button></a>
</div>
</div>
";
} else {
    http_response_code(404);
    echo "404 Not Found";
}
*/
