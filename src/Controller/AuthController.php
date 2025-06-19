<?php

namespace Varvara\Framework\Controller;

use Exception;
use PDO;
use PDOException;
use Varvara\Framework\Database\Database;

class AuthController
{
    public function showRegisterForm()
    {
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader);
        echo $twig->render('registerForm.html.twig');
    }

    public function showLoginForm()
    {
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader);
        echo $twig->render('loginForm.html.twig');
    }

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

            if ($count > 0) {
                echo 'This user already exists';
                die;
            }

            $stmt = $db->prepare('INSERT INTO "user" (email, password) VALUES (:email, :password)');
            $stmt->execute([
                'email' => $email,
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            ]);

            $loader = new \Twig\Loader\FilesystemLoader('templates');
            $twig = new \Twig\Environment($loader);

            if ($stmt->rowCount() > 0) {
                $value = 'Registration was successful!';
            } else {
                $value = "Something went wrong!";
            }
            echo $twig->render('success.html.twig', ['value' => $value]);

        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function login()
    {
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
                echo  $_SESSION['user_id'];
            }

            $loader = new \Twig\Loader\FilesystemLoader('templates');
            $twig = new \Twig\Environment($loader);

            if ($stmt->rowCount() > 0) {
                $value = 'Login was successful!';
            } else {
                $value = "Something went wrong!";
            }
            echo $twig->render('success.html.twig', ['value' => $value]);

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
