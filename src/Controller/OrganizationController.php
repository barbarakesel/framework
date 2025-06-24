<?php

namespace Varvara\Framework\Controller;

use Exception;
use PDO;
use PDOException;
use Varvara\Framework\Database\Database;

class OrganizationController
{
    public function show(): void
    {
        $userId = null;
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
        }

        $lang = trim((string) ($_COOKIE['lang'] ?? 'ru'));

        try {
            $database = new Database();

            $query = "SELECT id, name FROM organization WHERE owner = :userId";
            $params = [':userId' => $userId];

            if (isset($_GET['id'])) {
                $query .= " AND id = :id";
                $params[':id'] = $_GET['id'];
            }

            if (isset($_GET['name'])) {
                $query .= " AND name = :name";
                $params[':name'] = $_GET['name'];
            }

            $loader = new \Twig\Loader\FilesystemLoader('templates');
            $twig = new \Twig\Environment($loader);

            $results = $database->fetchAll($query, $params);
            if ($results) {
                echo $twig->render('organization.html.twig', ['results' => $results, 'lang' => $lang]);
            } else {
                echo $twig->render('organization.html.twig', ['lang' => $lang]);
            }

        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

    }

    public function create(): void
    {

        if (!isset($_SESSION['user_id'])) {
            echo "Please Login";
            return;
        }
        try {
            $database = new Database();
            $db = $database->getConnection();

            $name = $_POST['name'] ?? null;

            $query = 'INSERT INTO organization (name, owner) VALUES (:name, :owner)';
            $stmt = $db->prepare($query);
            $stmt->execute(['name' => $name, 'owner' => $_SESSION['user_id']]);

            $loader = new \Twig\Loader\FilesystemLoader('templates');
            $twig = new \Twig\Environment($loader);

            if ($stmt->rowCount() > 0) {
                $value = 'success';
            } else {
                $value = "fail";
            }
            header('Location: /organization?create=success');

        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function delete(): void
    {
        try {
            $database = new Database();
            $db = $database->getConnection();

            $id = $_POST['id'] ?? null;

            $query = 'DELETE FROM organization WHERE id = :id';
            $stmt = $db->prepare($query);
            $stmt->execute(['id' => $id]);

            $loader = new \Twig\Loader\FilesystemLoader('templates');
            $twig = new \Twig\Environment($loader);

            header('Location: /organization?delete=success');

        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function change(): void
    {
        try {
            $database = new Database();
            $db = $database->getConnection();

            $id = $_POST['id'] ?? null;

            $query = 'SELECT id, name FROM organization WHERE id = :id';
            $stmt = $db->prepare($query);
            $stmt->execute(['id' => $id]);
            $names = $stmt->fetchAll(PDO::FETCH_ASSOC);


            $loader = new \Twig\Loader\FilesystemLoader('templates');
            $twig = new \Twig\Environment($loader);
            echo $twig->render('changeForm.html.twig', ['names' => $names]);

        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function changeCompany(): void
    {
        try {
            $database = new Database();
            $db = $database->getConnection();

            $id = $_POST['id'] ?? null;
            $name = $_POST['name'] ?? null;


            $query = 'UPDATE organization SET name = :name WHERE id = :id';
            $stmt = $db->prepare($query);
            $stmt->execute(['id' => $id, 'name' => $name]);
            $names = $stmt->fetchAll(PDO::FETCH_ASSOC);


            $loader = new \Twig\Loader\FilesystemLoader('templates');
            $twig = new \Twig\Environment($loader);
            if ($stmt->rowCount() > 0) {
                $value = 'Company updated successfully!';
            } else {
                $value = "No company found with that ID = $id";
            }

            header('Location: /organization?update=success');

        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
