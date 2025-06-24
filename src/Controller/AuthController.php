<?php

namespace Varvara\Framework\Controller;

use Exception;
use PDO;
use PDOException;
use Varvara\Framework\Database\Database;

class AuthController
{
    public function register()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        try {
            $database = new Database();
            $db = $database->getConnection();

            $email = $_POST['email'];
            $stmt = $db->prepare('SELECT COUNT(*) FROM "user" WHERE email = :email');
            $stmt->execute(['email' => $email]);
            $count = $stmt->fetchColumn();

            header('Content-Type: application/json');

            if ($count > 0) {
                echo json_encode([
                    'success' => false,
                    'message' => 'This user already exists'
                ]);
                return;
            }

            $stmt = $db->prepare('INSERT INTO "user" (email, password) VALUES (:email, :password)');
            $stmt->execute([
                'email' => $email,
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            ]);

            echo json_encode([
                'success' => true,
                'message' => 'Registration successful'
            ]);
            return;

        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ]);
            return;
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
            return;
        }
    }


    public function login()
    {
        $lang = trim((string) ($_COOKIE['lang'] ?? 'ru'));

        if (session_status() === PHP_SESSION_NONE) {
            session_name("USER_AUTH");
            session_start();
        }

        try {
            $database = new Database();
            $db = $database->getConnection();

            $stmt = $db->prepare('SELECT * FROM "user" WHERE email = :email');
            $stmt->execute(['email' => $_POST['email']]);
            if (!$stmt->rowCount()) {
                echo 'Пользователь с такими данными не зарегистрирован';
                die;
            }
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($_POST['password'], $user['password'])) {
                if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
                    $newHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $stmt = $db->prepare('UPDATE "user" SET password = :password WHERE email = :email');
                    $stmt->execute([
                        'email' => $_POST['email'],
                        'password' => $newHash,
                    ]);
                }
                $_SESSION['user_id'] = $user['id'];
            }

            header('Location: /');

        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: /');
    }
}
