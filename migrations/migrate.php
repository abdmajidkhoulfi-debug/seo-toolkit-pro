<?php
/**
 * Migration runner
 * Usage: php migrations/migrate.php
 */

require_once __DIR__ . '/../src/autoload.php';

$config = require __DIR__ . '/../config/app.php';
$db = \App\Core\Database::getInstance($config['database']);
$pdo = $db->getPdo();

// Create migrations tracking table
$pdo->exec("
    CREATE TABLE IF NOT EXISTS migrations (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL UNIQUE,
        executed_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )
");

$migrationFiles = glob(__DIR__ . '/*.php');
sort($migrationFiles);

$executed = $pdo->query("SELECT name FROM migrations")->fetchAll(\PDO::FETCH_COLUMN);

foreach ($migrationFiles as $file) {
    $name = basename($file);

    if ($name === 'migrate.php') {
        continue;
    }

    if (in_array($name, $executed)) {
        echo "Skipped: {$name} (already executed)\n";
        continue;
    }

    echo "Running: {$name}...\n";

    $migration = require $file;
    $migration($pdo);

    $pdo->prepare("INSERT INTO migrations (name) VALUES (?)")->execute([$name]);
    echo "Done: {$name}\n";
}

echo "\nAll migrations completed.\n";
