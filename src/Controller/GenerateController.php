<?php

namespace Varvara\Framework\Controller;

use Faker\Factory;
use PDOException;
use Varvara\Framework\Database\Database;

class GenerateController
{
    public function generate(int $quantity): void
    {
        $lang = trim((string) ($_COOKIE['lang'] ?? 'ru'));
        try {
            $database = new Database();
            $db = $database->getConnection();

            $faker = Factory::create();

            $organizationId = $_POST['organization_id'] ?? null;

            for ($i = 0; $i < $quantity; $i++) {
                $data = [
                    'organization_id' => $organizationId,
                    'country' => $faker->country,
                    'city' => $faker->city,
                    'is_active' => $faker->numberBetween(0, 1),
                    'gender' => $faker->randomElement($array = ['Female', 'Male']),
                    'birth_date' => $faker->date,
                    'salary' => $faker->numberBetween($min = 400, $max = 5000),
                    'has_children' => $faker->numberBetween(0, 1),
                    'family_status' => $faker->randomElement($array = ['married', 'single']),
                    'registration_date' => $faker->date
                ];
                $query = 'INSERT INTO users (
                            organization_id, country, city, is_active, gender, birth_date, salary, has_children, family_status, registration_date
                        ) VALUES (
                            :organization_id, :country, :city, :is_active, :gender, :birth_date, :salary, :has_children, :family_status, :registration_date
                        )';

                $database->execute($query, $data);
            }

            header('Location: /?generate=success');

        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

    }
}