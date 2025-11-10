<?php

return new class {
    public function up(PDO $pdo) {
        // Exemplo:
        // $pdo->exec("CREATE TABLE users (
        //     id INT AUTO_INCREMENT PRIMARY KEY,
        //     name VARCHAR(100)
        // )");
    }

    public function down(PDO $pdo) {
        // Exemplo:
        // $pdo->exec("DROP TABLE users");
    }
};