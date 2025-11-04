<?php
$config = require __DIR__ . '/../config/database.php';

try {
    $pdo = new PDO(
        "mysql:host={$config['host']};dbname={$config['dbname']}",
        $config['user'],
        $config['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Garante que a tabela de controle exista
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) UNIQUE,
            migrated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    $migrated = $pdo->query("SELECT name FROM migrations")->fetchAll(PDO::FETCH_COLUMN);

    $files = glob(__DIR__ . '/../database/migrations/*.php');
    $executadas = 0;

    foreach ($files as $file) {
        $name = basename($file);

        if (in_array($name, $migrated)) {
            echo "⏩ Ignorando (já migrada): {$name}\n";
            continue;
        }

        echo "🚀 Executando migration: {$name}\n";

        try {
            $migration = require $file;
            $migration->up($pdo);

            $stmt = $pdo->prepare("INSERT INTO migrations (name) VALUES (?)");
            $stmt->execute([$name]);

            echo "✅ Migration concluída: {$name}\n\n";
            $executadas++;
        } catch (Exception $e) {
            echo "❌ Erro ao executar {$name}: " . $e->getMessage() . "\n\n";
        }
    }

    if ($executadas === 0) {
        echo "Nenhuma migration nova para executar.\n";
    } else {
        echo "🎉 {$executadas} migration(s) executadas com sucesso!\n";
    }

} catch (PDOException $e) {
    echo "❌ Erro de conexão com o banco de dados: " . $e->getMessage() . "\n";
    exit(1);
}
