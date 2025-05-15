<?php
declare(strict_types=1);

namespace Varvara\Framework\App;

require_once __DIR__ . '/../../vendor/autoload.php';

use Faker\Factory;
use Exception;
use PDOException;

class Generate
{
    public function generate(int $quantity): void {
        try {
            $database = new DatabaseConnection();
            $db = $database->getConnection();

            $faker = Factory::create();

            for ($i = 0; $i < $quantity; $i++) {
                $data = [
                    'country' => $faker->country,
                    'city' => $faker->city,
                    'is_active' => $faker->numberBetween(0, 1),
                    'gender' => $faker->randomElement($array = array ('Female', 'Male')),
                    'birth_date' => $faker->date,
                    'salary' => $faker->numberBetween($min = 400, $max = 5000),
                    'has_children' => $faker->numberBetween(0, 1),
                    'family_status' => $faker->randomElement($array = array ('married', 'single')),
                    'registration_date' => $faker->date
                ];
                $stmt = $db->prepare('INSERT INTO users (country, city, is_active, gender, birth_date, salary, has_children, family_status, registration_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
                $stmt->execute([
                    $data['country'],
                    $data['city'],
                    $data['is_active'],
                    $data['gender'],
                    $data['birth_date'],
                    $data['salary'],
                    $data['has_children'],
                    $data['family_status'],
                    $data['registration_date']
                ]);
            }
            echo "
                    <div style='background: lightpink; color: white; padding: 20px;  height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; '>
                    <h1> Data generated successfully! </h1>
                    <div style='display:flex; justify-content:center; flex-direction:column; '>
                                <a href = '/' style = 'padding: 20px; '><button style = 'width: 250px; height: 50px; font-size: 20px; border-radius: 12px; background: white'>Main Page</button></a>
                    </div>
                    </div>
                    ";
        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

    }
}