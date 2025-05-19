<?php

declare(strict_types=1);

namespace Varvara\Framework\App;

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
            $filename = $_FILES['csv']['tmp_name'];


            if (!file_exists($filename)) {
                die("File not found: $filename");
            }

            $handle = fopen($filename, "r");

            if (!$handle) {
                die("Unable to open the file: $filename");
            }

            $maxFileSize = 5 * 1024 * 1024;

            if ($filename > $maxFileSize) {
                echo "This file is too big!";
                exit;
            }
            $rowNum = 0;
            while (($row = fgetcsv($handle, 1000, ',', '"', '\\')) !== false) {
                $rowNum++;

                if (!$row || count($row) < 9) {
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
                    'country' => trim($row[0] ?? ''),
                    'city' => trim($row[1] ?? ''),
                    'is_active' => (int)$row[2],
                    'gender' => trim($row[3] ?? ''),
                    'birth_date' => $birthDate->format('Y-m-d'),
                    'salary' => is_numeric($row[5]) ? (int)$row[5] : 0,
                    'has_children' => (int)$row[6],
                    'family_status' => trim($row[7] ?? ''),
                    'registration_date' => $registrationDate->format('Y-m-d')
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

            fclose($handle);
            echo "
                    <div style='background: lightpink; color: white; padding: 20px;  height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; '>
                    <h1> Data imported successfully! </h1>
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
//                        <a href = '/parse' style = 'padding: 20px;'><button style = 'width: 250px; height: 50px; font-size: 20px; border-radius: 12px; background: white'>Parse Data</button></a>
