<?php

namespace Varvara\Framework\Controller;

class FileController
{
    public function file(): void
    {
        header('Location: /');
    }
    public function upload(): void
    {
        echo "f";
        echo $_FILES['csv']['tmp_name'];
    }
}
