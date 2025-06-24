<?php

namespace Varvara\Framework\Controller;

class LanguageController
{
    public function setLanguage()
    {
        setcookie('lang', $_GET['lang'], time() + 3600 * 24 * 30);
        header('Location: /');
        exit;
    }
}