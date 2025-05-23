<?php

namespace Varvara\Framework\Controller;

use Varvara\Framework\Database\Database;

class StatController
{
    public function countByField(string $field): void
    {
        $db = new Database();
        $query = "SELECT $field as label, COUNT(*) as count FROM users GROUP BY $field";
        $data = $db->fetchAll($query);

        header('Content-Type: application/json');
        echo json_encode($data);
        // echo $twig->render('statistics.html.twig');

        exit;
    }

    public function showStatPage(): void
    {
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader);
        echo $twig->render('statistics.html.twig');
    }


}
