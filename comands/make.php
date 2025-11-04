<?php
// commands/make.php
// Uso: php commands/make.php controller Nome
//      php commands/make.php model Nome
//      php commands/make.php view nome_view

$type = $argv[1] ?? null;
$name = $argv[2] ?? null;

if (!$type || !$name) {
    echo "❌ Uso: php commands/make.php [controller|model|view] Nome\n";
    exit;
}

$type = strtolower($type);
$name = ucfirst($name);
$baseDir = __DIR__ . '/../app';
$stubDir = __DIR__ . '/stubs';

switch ($type) {
    case 'controller':
        $namespace = "App\\Controllers";
        $className = "{$name}Controller";
        $path = "{$baseDir}/Controllers/{$className}.php";
        $stubFile = "{$stubDir}/controller.stub";
        $replacements = [
            '{{namespace}}' => $namespace,
            '{{class}}' => $className
        ];
        break;

    case 'model':
        $namespace = "App\\Models";
        $className = $name;
        $path = "{$baseDir}/Models/{$className}.php";
        $stubFile = "{$stubDir}/model.stub";
        $replacements = [
            '{{namespace}}' => $namespace,
            '{{class}}' => $className,
            '{{table}}' => strtolower("{$name}s")
        ];
        break;

    case 'view':
        $nameLower = strtolower($argv[2]);
        $path = "{$baseDir}/Views/{$nameLower}.php";
        $stubFile = "{$stubDir}/view.stub";
        $replacements = [
            '{{name}}' => $nameLower
        ];
        break;

    default:
        echo "❌ Tipo inválido. Use: controller | model | view\n";
        exit;
}

if (!file_exists($stubFile)) {
    echo "❌ Arquivo stub não encontrado: {$stubFile}\n";
    exit;
}

if (file_exists($path)) {
    echo "⚠️  O arquivo já existe: {$path}\n";
    exit;
}

// Lê o conteúdo do stub e substitui os placeholders
$template = file_get_contents($stubFile);
$content = str_replace(array_keys($replacements), array_values($replacements), $template);

file_put_contents($path, $content);
echo "✅ {$type} criado com sucesso em: {$path}\n";
