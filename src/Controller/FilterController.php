<?php

namespace Varvara\Framework\Controller;

use Exception;
use PDOException;
use Varvara\Framework\Database\Database;

class FilterController
{
    public function filter(): void
    {
        $lang = trim((string) ($_COOKIE['lang'] ?? 'ru'));

        $userId = null;

        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
        }
        try {
            $database = new Database();

            $query = "SELECT * FROM users WHERE 1=1";
            $params = [];

            if (isset($_GET['country'])) {
                $query .= " AND country = :country";
                $params[':country'] = $_GET['country'];
            }

            if (isset($_GET['city'])) {
                $query .= " AND city = :city";
                $params[':city'] = $_GET['city'];
            }

            if (isset($_GET['is_active'])) {
                $query .= " AND is_active = :is_active";
                $params[':is_active'] = $_GET['is_active'];
            }

            if (isset($_GET['gender'])) {
                $query .= " AND gender = :gender";
                $params[':gender'] = $_GET['gender'];
            }

            if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
                $query .= " AND birth_date BETWEEN :start_date AND :end_date";
                $params[':start_date'] = $_GET['start_date'];
                $params[':end_date'] = $_GET['end_date'];
            }

            $loader = new \Twig\Loader\FilesystemLoader('templates');
            $twig = new \Twig\Environment($loader);

            $results = $database->fetchAll($query, $params);
            if ($results) {
                echo $twig->render('filter.html.twig', ['results' => $results, 'lang' => $lang]);
            } else {
                if ($lang == 'ru') {
                    $value = 'Результаты не найдены!';
                } else {
                    $value = 'There are no results!';
                }
                echo $twig->render('success.html.twig', ['value' => $value, 'lang' => $lang]);
            }

        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

    }
}
