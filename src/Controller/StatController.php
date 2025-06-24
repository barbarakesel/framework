<?php

namespace Varvara\Framework\Controller;

use PDO;
use Varvara\Framework\Database\Database;

class StatController
{
    public function countByField(string $organizationId, string $field): void
    {
        $organizationId = (int) $organizationId;

        $allowedFields = ['gender', 'country', 'is_active', 'has_children', 'family_status'];

        if (!in_array($field, $allowedFields, true)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid field']);
            exit;
        }

        $db = new Database();
        $query = "SELECT $field as label, COUNT(*) as count 
              FROM users 
              WHERE organization_id = :organization_id 
              GROUP BY $field";

        $data = $db->fetchAll($query, ['organization_id' => $organizationId]);

        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function showStatPage(): void
    {
        $userId = null;
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
        };

        $lang = trim((string) ($_COOKIE['lang'] ?? 'ru'));

        $database = new Database();
        $db = $database->getConnection();

        $query = 'SELECT id, name FROM organization WHERE owner = :userId';
        $params = [':userId' => $userId];
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $names = $stmt->fetchAll(PDO::FETCH_ASSOC);


        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader);
        echo $twig->render('statistics.html.twig', ['names' => $names, 'lang' => $lang]);
    }
}
