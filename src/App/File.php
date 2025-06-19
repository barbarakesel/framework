<?php

namespace Varvara\Framework\App;

class File
{
    public function file(): void
    {
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader);
        echo $twig->render('uploadFile.html.twig');
    }
    public function upload(): void
    {
        echo "f";
        // echo $_FILES["csv"]["name"];
        echo $_FILES['csv']['tmp_name'];
        ;
    }
}
