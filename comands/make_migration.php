<?php

$name = $argv[1] ?? null;

if (!$name) {
    die("Uso: php database/make_migration.php nome_da_migration\n");
}

$timestamp = date('Y_m_d_His');
$filename = __DIR__ . "/../database/migrations/{$timestamp}_{$name}.php";

$template = <<<PHP
<?php

return new class {
    public function up(PDO \$pdo) {
        // Exemplo:
        // \$pdo->exec("CREATE TABLE users (
        //     id INT AUTO_INCREMENT PRIMARY KEY,
        //     name VARCHAR(100)
        // )");
    }

    public function down(PDO \$pdo) {
        // Exemplo:
        // \$pdo->exec("DROP TABLE users");
    }
};
PHP;

if (file_put_contents($filename, $template) !== false) {
    echo "✅ Migration criada: {$filename}\n";
} else {
    echo "❌ Erro ao criar a migration.\n";
}
