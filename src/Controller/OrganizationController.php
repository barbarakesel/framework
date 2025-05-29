<?php

namespace Varvara\Framework\Controller;

use Exception;
use Faker\Factory;
use PDO;
use PDOException;
use Varvara\Framework\Database\Database;

class OrganizationController
{
    public function show() :void
    {
        try {
        $database = new Database();

        $query = "SELECT * FROM organization WHERE 1=1";
        $params = [];

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
            echo $twig->render('organization.html.twig', ['results' => $results]);
        } else {
            echo $twig->render('noResults.html.twig');
        }

        } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

    }

    public function showCreateForm() :void
    {
        echo "<div style='background: lightpink; color: white; padding: 20px;  height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; '>
          <form method='POST' action='/organization/create'>
                <input name='name' style = 'width: 250px; height: 50px; font-size: 20px; border-radius: 12px; background: white'>
                <button type='submit' style = 'width: 250px; height: 50px; font-size: 20px; border-radius: 12px; background: white'>Create</button>
          </form>
          </div>";
    }
    public function showDeleteForm() :void
    {
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader);
        echo $twig->render('deleteForm.html.twig');
    }

    public function showChangeForm() :void
    {
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader);
        echo $twig->render('changeFormId.html.twig');
    }
    public function create() :void
    {
        try {
            $database = new Database();
            $db = $database->getConnection();

            $name = $_POST['name'] ?? null;

            $query = 'INSERT INTO organization (name) 
                          VALUES (:name)';
            $stmt = $db->prepare($query);
            $stmt->execute(['name' => $name]);

            $loader = new \Twig\Loader\FilesystemLoader('templates');
            $twig = new \Twig\Environment($loader);

            if ($stmt->rowCount() > 0) {
                $value = 'Company created successfully!';
            } else {
                $value = "Company not created!";
            }
            echo $twig->render('success.html.twig', ['value' => $value]);

        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function delete() :void
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

            if ($stmt->rowCount() > 0) {
                $value = 'Company deleted successfully!';
            } else {
                $value = "No company found with that ID = $id";
            }

            echo $twig->render('success.html.twig', ['value' => $value]);

        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function change() :void
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

    public function changeCompany() :void
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

            echo $twig->render('success.html.twig', ['value' => $value]);

        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}