# Iniciar
1. Intale o php(versão 8.3.14 ou superior) e mysql na sua máquina
2. Para iniciar crie um documento .env como cita o exemplo no código, preencha com as credenciais do banco de dados que for usar
3. Crie um banco de dados compatível com o que preencheu no .env
4. Rode as migrations para criar as tabelas

# Comandos
php comands/make_migration.php nome_da_migration // cria uma migration
php comands/migrate.php // roda as migrations
php comands/migrate_rollback.php // desfaz o migrate
php comands/make.php [controller|model|view] NomeDoArquivo // cria um controler, ou model, ou view