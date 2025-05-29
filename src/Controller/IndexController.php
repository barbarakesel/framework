<?php

namespace Varvara\Framework\Controller;

class IndexController
{
    public function index(): void
    {
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader);
        echo $twig->render('index.html.twig');
    }

    public function showGenerateForm(): void
    {
        echo "<div style='background: lightpink; color: white; padding: 20px;  height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; '>
          <form method='POST' action='/generate'>
                <input type='number' name='quantity' min='1' max='100' style = 'width: 250px; height: 50px; font-size: 20px; border-radius: 12px; background: white' value='quantityForm'>
                <button type='submit' style = 'width: 250px; height: 50px; font-size: 20px; border-radius: 12px; background: white'>Generate</button>
          </form>
          </div>";
    }

}
