<?php

declare(strict_types=1);

use App\Parse;
use App\Filter;

$filter = new Filter();
$parse = new Parse();
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestUri = trim($requestUri, '/');

if ($requestUri == 'filter') {
    $filter->filter();
} else if ($requestUri == 'parse') {
    $parse->parse();
} else if ($requestUri == '') {
    echo "<h1> Welcome to main page <h1>";
} else {
    http_response_code(404);
    echo "404 Not Found";
}

