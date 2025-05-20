<?php

declare(strict_types=1);

namespace Varvara\Framework\App;

use Exception;
use PDOException;
use Varvara\Framework\Database\Database;

class Filter
{
    public function filter(): void
    {
        try {
            $database = new Database();

            $query = "SELECT * FROM users WHERE 1=1";
            $params = [];

            if (isset($_GET['country'])) {
                $query .= " AND country = :country";
                $params[':country'] = $_GET['country'];
            }

            if (isset($_GET['city'])) {
                $query .= " AND city = :city";
                $params[':city'] = $_GET['city'];
            }

            if (isset($_GET['is_active'])) {
                $query .= " AND is_active = :is_active";
                $params[':is_active'] = $_GET['is_active'];
            }

            if (isset($_GET['gender'])) {
                $query .= " AND gender = :gender";
                $params[':gender'] = $_GET['gender'];
            }

            if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
                $query .= " AND birth_date BETWEEN :start_date AND :end_date";
                $params[':start_date'] = $_GET['start_date'];
                $params[':end_date'] = $_GET['end_date'];
            }


            $results = $database->fetchAll($query, $params);
            if ($results) {
                echo "
                <div style='background: lightpink; color: white; padding: 20px;  height: 100vh; display: flex; flex-direction: column; align-items: center; text-align: center; '>
                <div style='display:flex; justify-content:center; flex-direction:column; '>
                                <a href = '/' style = 'padding: 20px; '><button style = 'width: 250px; height: 50px; font-size: 20px; border-radius: 12px; background: white'>Main Page</button></a>
                </div>
                <table style='border: 1px solid black; border-collapse: collapse; width: 100%; background: #ffffff;'>
                <thead>
                    <tr style='background-color: lightpink;'>
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
                <tbody>
                </div>";
                foreach ($results as $row) {
                    echo "<tr>";
                    foreach ($row as $column => $value) {
                        echo "<td style='padding: 10px'>$value</td>";
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
