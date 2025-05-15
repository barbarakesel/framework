<?php

declare(strict_types=1);

require_once "../vendor/autoload.php";

use Varvara\Framework\App\Filter;
use Varvara\Framework\App\Generate;
use Varvara\Framework\App\Parse;

$filter = new Filter();
$parse = new Parse();
$generate = new Generate();

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestUri = trim($requestUri, '/');

function showGenerateForm()
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
} else if ($requestUri == 'parse') {
    $parse->parse();
} else if ($requestUri == 'generate') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $quantity = (int)$_POST['quantity'] ?? 0;
        $generate->generate($quantity);
    } else {
        showGenerateForm();
    }
} else if ($requestUri == '') {
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
