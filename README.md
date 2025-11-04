# Iniciar
1. Instale o php(versão 8.3.14 ou superior) e mysql na sua máquina
2. Para iniciar crie um documento .env como cita o exemplo no código, preencha com as credenciais do banco de dados que for usar
3. Crie um banco de dados compatível com o que preencheu no .env
4. Rode as migrations para criar as tabelas

# Comandos
1. php comands/make_migration.php nome_da_migration // cria uma migration
2. php comands/migrate.php // roda as migrations
3. php comands/migrate_rollback.php // desfaz o migrate
4. php comands/make.php [controller|model|view] NomeDoArquivo // cria um controler, ou model, ou view
5. php -S localhost:8000 -t public // use esse comando para expor o sistema, acesse pelo navegador através da url http://localhost:800
