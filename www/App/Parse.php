<?php

declare(strict_types=1);

namespace App;
use App\DatabaseConnection;
use DateTime;
use Exception;
use PDOException;

class Parse
{
    public function parse(): void
    {
        try {
            $database = new DatabaseConnection();
            $db = $database->getConnection();

            $filename = '/var/www/html/data/data.csv';

            if (!file_exists($filename)) {
                die("File not found: $filename");
            }

            $handle = fopen($filename, "r");

            if (!$handle) {
                die("Unable to open the file: $filename");
            }

            $rowNum = 0;
            while (($row = fgetcsv($handle, 1000, ',', '"', '\\')) !== FALSE) {
                $rowNum++;

                if ($row === false || count($row) < 9) {
                    echo "Skipping row $rowNum: Invalid row data\n";
                    continue;
                }

                $birthDate = DateTime::createFromFormat('d.m.Y', $row[4]);
                $registrationDate = DateTime::createFromFormat('d.m.Y', $row[8]);

                if (!$birthDate || !$registrationDate) {
                    echo "Skipping row $rowNum: Invalid date format\n";
                    continue;
                }

                $data = [
                    'country' => trim($row[0]),
                    'city' => trim($row[1]),
                    'is_active' =>  (int)$row[2],
                    'gender' => trim($row[3]),
                    'birth_date' => $birthDate->format('Y-m-d'),
                    'salary' => is_numeric($row[5]) ? (int)$row[5] : 0,
                    'has_children' =>  (int)$row[6],
                    'family_status' => trim($row[7]),
                    'registration_date' => $registrationDate->format('Y-m-d')
                ];

                $stmt = $db->prepare('INSERT INTO users (country, city, is_active, gender, birth_date, salary, has_children, family_status, registration_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');

                if ($stmt->execute([
                    $data['country'],
                    $data['city'],
                    $data['is_active'],
                    $data['gender'],
                    $data['birth_date'],
                    $data['salary'],
                    $data['has_children'],
                    $data['family_status'],
                    $data['registration_date']
                ])) {
                } else {
                    echo "Failed to insert row $rowNum\n";
                }
            }

            fclose($handle);
            echo "Data imported successfully!";
        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

    }
}