<?php

declare(strict_types=1);

use PDO;
use PDOException;
use Exception;

class Filter
{
    public function filter(): void
    {

        try {
            $database = new DatabaseConnection();
            $db = $database->getConnection();

            $stmt = "SELECT * FROM users WHERE 1=1";
            $params = [];

            if (isset($_GET['country'])) {
                $stmt .= " AND country = :country";
                $params[':country'] = $_GET['country'];
            }

            if (isset($_GET['city'])) {
                $stmt .= " AND city = :city";
                $params[':city'] = $_GET['city'];
            }

            if (isset($_GET['is_active'])) {
                $stmt .= " AND is_active = :is_active";
                $params[':is_active'] = $_GET['is_active'];
            }

            if (isset($_GET['gender'])) {
                $stmt .= " AND gender = :gender";
                $params[':gender'] = $_GET['gender'];
            }

            if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
                $stmt .= " AND birth_date BETWEEN :start_date AND :end_date";
                $params[':start_date'] = $_GET['start_date'];
                $params[':end_date'] = $_GET['end_date'];
            }

            $query = $db->prepare($stmt);
            $query->execute($params);

            $results = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($results) {
                echo "<table style='border: 1px solid black; border-collapse: collapse; width: 100%;'>
                <thead>
                    <tr style='background-color: #f2f2f2;'>
                        <th style='padding: 10px; text-align: left;'>ID</th>
                        <th style='padding: 10px; text-align: left;'>Country</th>
                        <th style='padding: 10px; text-align: left;'>City</th>
                        <th style='padding: 10px; text-align: left;'>Is Active</th>
                        <th style='padding: 10px; text-align: left;'>Gender</th>
                        <th style='padding: 10px; text-align: left;'>Birth Date</th>
                        <th style='padding: 10px; text-align: left;'>Salary</th>
                        <th style='padding: 10px; text-align: left;'>Has Children</th>
                        <th style='padding: 10px; text-align: left;'>Family Status</th>
                        <th style='padding: 10px; text-align: left;'>Registration Date</th>
                    </tr>
                </thead>
                <tbody>";
                foreach ($results as $row) {
                    echo "<tr>";
                    foreach ($row as $column => $value) {
                        echo "<td>$value</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No results found.";
            }

        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

    }
}