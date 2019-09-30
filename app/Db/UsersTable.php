<?php

namespace App\Db;

class UsersTable extends \App\MakeTable 
{
   protected $table = 'Users';

   public function __construct($db) {
        parent::__construct($db, $this->table);

        $this->columns = [
            'id'            => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'username'      => 'VARCHAR(15) NOT NULL',
            'password'      => 'VARCHAR(72) NOT NULL',
            'email'         => 'VARCHAR(120)'
        ];

        $this->seed = [
            [
                'username'  => 'lcloss',
                'password'  => password_hash('1234', PASSWORD_DEFAULT),
                'email'     => 'lcloss@gmail.com'
            ],
        ];
    }
}