<?php

require_once "vendor/autoload.php";

use Dotenv\Dotenv;
use Varvara\Framework\Database\Database;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $db = new Database();
    $pdo = $db->getConnection();

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS migrations (
            id SERIAL PRIMARY KEY,
            migration VARCHAR(255) UNIQUE NOT NULL,
            applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    $stmt = $pdo->query("SELECT migration FROM migrations");
    $appliedMigrations = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $migrationFiles = array_filter(scandir(__DIR__ . '/migrations'), fn ($file) => preg_match('/\.sql$/', $file));
    sort($migrationFiles);

    foreach ($migrationFiles as $file) {
        if (in_array($file, $appliedMigrations, true)) {
            echo "Миграция $file уже применена\n";
            continue;
        }

        echo "Применяем миграцию $file...\n";

        $sql = file_get_contents(__DIR__ . "/migrations/$file");

        try {
            $pdo->beginTransaction();
            $pdo->exec($sql);
            $stmt = $pdo->prepare("INSERT INTO migrations (migration) VALUES (:migration)");
            $stmt->execute(['migration' => $file]);
            $pdo->commit();
            echo "Миграция $file применена успешно\n";
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo "Ошибка при применении миграции $file: " . $e->getMessage() . "\n";
            exit(1);
        }
    }

    echo "Все миграции выполнены\n";

} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
    exit(1);
}
