<?php
$config = require __DIR__ . '/../config/database.php';
$pdo = new PDO("mysql:host={$config['host']};dbname={$config['dbname']}", $config['user'], $config['password']);
$migration = $pdo->query("SELECT name FROM migrations ORDER BY id DESC LIMIT 1")->fetchColumn();

if ($migration) {
    require __DIR__ . "/../database/migrations/{$migration}";
    $class = require __DIR__ . "/../database/migrations/{$migration}";
    $class->down($pdo);
    $pdo->exec("DELETE FROM migrations WHERE name = '{$migration}'");
    echo "Rollback executado: {$migration}\n";
} else {
    echo "Nenhuma migration para reverter.\n";
}