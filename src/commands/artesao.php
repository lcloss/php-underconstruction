<?php
if ( count($argv) < 2 ) {
    echo "Erro de sintaxe. Por favor informe:\n\r\n\r$ php artesao <comando> [<parametros>] \n\r\n\r";
    echo "Para uma lista dos comandos, digite: \n\r\n\r$ php artesao list\n\r";
    exit(0);
}

require 'vendor/autoload.php';
require 'src/config/bootstrap.php';

switch( $argv[1] ) {
    case 'list':
        $available_cmds = array(
            'list',
            'create:controller <controller>',
            'delete:controller <controller>',
            'create:middleware <middleware>',
            'create:table <table>',
            'make:table <table>',
            'seed:table <table>',
            'drop:table <table>',
            'remove:table <table>',
            'update:table <table>',
            'update:domain <domain> <user> <dbname> <dbpassw>',
        );
    
        echo "Lista de comandos disponíveis:\n\r\n\r";
        foreach($available_cmds as $command) {
            echo "$ php artesao " . $command . "\n\r";
        }
        break;

    case 'create:controller':
        if ( count($argv) != 3 ) {
            echo "Erro de sintaxe. Por favor informe:\n\r\n\r$ php artesao " . $argv[1] . " <controller> \n\r";
            exit(0);
        }

        echo "A criar o controller " . $argv[2] . "\n\r";
        $controller = new \App\MakeController($argv[2]);
        $controller->create();
        echo "Controller " . $argv[2] . " criado.\n\r\n\r";
        echo "Crie agora os templates em app/Views/" . strtolower($argv[2]) . "/\n\r";
        echo "Crie também uma entrada em src/config/routes.php\n\r";
        break;

    case 'delete:controller':
        if ( count($argv) < 3 ) {
            echo "Erro de sintaxe. Por favor informe:\n\r\n\r$ php artesao " . $argv[1] . " <controller> \n\r";
            exit(0);
        }

        echo "A eliminar o controller " . $argv[2] . "\n\r";
        $controller = new \App\MakeController($argv[2]);
        $controller->drop();
        echo "Controller " . $argv[2] . " eliminado.\n\r";
        break;

    case 'create:middleware':
        if ( count($argv) < 3 ) {
            echo "Erro de sintaxe. Por favor informe:\n\r\n\r$ php artesao " . $argv[1] . " <middleware> \n\r";
            exit(0);
        }

        echo "A criar o middleware " . $argv[2] . "\n\r";
        break;

    case 'create:table':
        if ( count($argv) < 3 ) {
            echo "Erro de sintaxe. Por favor informe:\n\r\n\r$ php artesao " . $argv[1] . " <table> \n\r";
            exit(0);
        }

        echo "A criar a tabela " . $argv[2] . "\n\r";
        $c = $app->getContainer();
        $table = new \App\MakeTable($c->db, $argv[2]);
        $table->create();
        echo "Tabela criada. Altere e execute make:table " . $argv[2] . "\n\r";
        break;

    case 'make:table':
        echo "A criar a tabela " . $argv[2] . " na base de dados.\n\r";
        $classname = '\\App\\Db\\' . $argv[2] . 'Table';
        $c = $app->getContainer();
        $table = new $classname($c->db);
        $table->make();
        echo "Tabela criada na base de dados. Faça seed:table " . $argv[2] . " para criar os dados.\n\r";
        break;

    case 'seed:table':
        echo "A inserir dados na tabela " . $argv[2] . "\n\r";
        $classname = '\\App\\Db\\' . $argv[2] . 'Table';
        $c = $app->getContainer();
        $table = new $classname($c->db);
        $n_rows = $table->seed();
        echo "Tabela " . $argv[2] . " com " . $n_rows . " linhas adicionadas.\n\r";
        break;

    case 'drop:table':
        echo "A eliminar a tabela " . $argv[2] . " da base de dados.\n\r";
        $classname = '\\App\\Db\\' . $argv[2] . 'Table';
        $c = $app->getContainer();
        $table = new $classname($c->db);
        $table->drop();
        echo "Tabela " . $argv[2] . " eliminada.\n\r";
        break;

    case 'remove:table':
        echo "A eliminar a tabela " . $argv[2] . " do sistema.\n\r";
        $classname = '\\App\\Db\\' . $argv[2] . 'Table';
        $c = $app->getContainer();
        $table = new $classname($c->db);
        $table->remove();
        echo "Tabela " . $argv[2] . " removida.\n\r";
        break;

    case 'update:table':
        echo "A alterar a tabela " . $argv[2] . " do sistema.\n\r";
        $classname = '\\App\\Db\\' . $argv[2] . 'Table';
        $c = $app->getContainer();
        $table = new $classname($c->db);
        $table->update();
        echo "Tabela " . $argv[2] . " atualizada.\n\r";
        break;

    case 'update:domain':
        if ( count($argv) != 9 ) {
            echo "Erro de sintaxe. Por favor informe:\n\r\n\r$ php artesao " . $argv[1] . " <domain> <user> <email> <password> <dbname> <dbuser> <dbpasswd>\n\r";
            exit(0);
        }
        $domain = new \App\UpdateEnv($argv[2], $argv[3], $argv[4], $argv[5], $argv[6], $argv[7], $argv[8]); 
        $domain->updateDomain($argv[2], true);
        $domain->update();
        echo "Domínio " . $argv[2] . " atualizado.\n\r\n\r";
        echo "Verifique agora o ficheiro .env\n\r";
        break;

    default:
        echo "Erro de sintaxe.\n\rPor favor informe:\n\r\n\r$ php artesao <comando> <parametros>\n\r\n\r";
        echo "Para a lista de comandos disponíveis, escreva:\n\r\n\r$ php artesao list\n\r\n\r\n\r";
        exit(0);
}

