<?php
if ( count($argv) < 2 ) {
    echo "Erro de sintaxe. Por favor informe:\n\r\n\r$ php artesao <comando> [<parametros>] \n\r";
    exit(0);
}

require 'vendor/autoload.php';
require 'src/config/bootstrap.php';

switch( $argv[1] ) {
    case 'list':
        $available_cmds = array(
            'list',
            'create:controller <controller>',
            'drop:controller <controller>',
            'create:middleware <middleware>',
            'create:table <table>',
            'make:table <table>',
            'seed:table <table>',
            'drop:table <table>'
        );
    
        echo "Lista de comandos disponíveis:\n\r\n\r";
        foreach($available_cmds as $command) {
            echo "$ php artesao " . $command . "\n\r";
        }
        break;

    case 'create:controller':
        if ( count($argv) < 3 ) {
            echo "Erro de sintaxe. Por favor informe:\n\r\n\r$ php artesao " . $argv[1] . " <controller> \n\r";
            exit(0);
        }

        echo "A criar o controller " . $argv[2] . "\n\r";
        $controller = new \App\MakeController($argv[2]);
        $controller->create();
        echo "Controller " . $argv[2] . " criado. Crie agora os templates em app/Views/" . strtolower($argv[2]) . "/\n\r";
        break;

    case 'drop:controller':
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
        $table = new \App\MakeTable($argv[2]);
        $table->create();
        echo "Tabela criada. Altere e execute make:table " . $argv[2];
        break;

    case 'make:table':
        echo "A criar a tabela " . $argv[2] . " na base de dados.\n\r";
        $classname = '\\App\\Db\\' . $argv[2] . 'Table';
        $c = $app->getContainer();
        $table = new $classname($c->db);
        $table->make();
        echo "Tabela criada na base de dados. Faça seed:table " . $argv[2] . " para criar os dados.";
        break;

    case 'seed:table':
        echo "A inserir dados na tabela " . $argv[2] . "\n\r";
        $classname = '\\App\\Db\\' . $argv[2] . 'Table';
        $c = $app->getContainer();
        $table = new $classname($c->db);
        $n_rows = $table->seed();
        echo "Tabela " . $argv[2] . " com " . $n_rows . " linhas adicionadas.";
        break;

    case 'drop:table':
        echo "A eliminar a tabela " . $argv[2] . " da base de dados.\n\r";
        $classname = '\\App\\Db\\' . $argv[2] . 'Table';
        $c = $app->getContainer();
        $table = new $classname($c->db);
        $table->drop();
        echo "Tabela " . $argv[2] . " eliminada.";
        break;

    default:
        echo "Erro de sintaxe.\n\rPor favor informe:\n\r\n\r$ php artesao <comando> <parametros>\n\r\n\r";
        echo "Para a lista de comandos disponíveis, escreva:\n\r\n\r$ php artesao list\n\r\n\r\n\r";
        exit(0);
}

