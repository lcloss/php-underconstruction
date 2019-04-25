<?php

namespace App\Db;

class NotifierListTable extends \App\MakeTable 
{
   protected $table = 'NotifierList';

   public function __construct($db) {
        parent::__construct($db, $this->table);

        $this->columns = [
            'id'        => 'INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
            'email'     => 'VARCHAR(150) NOT NULL'
        ];

        $this->seed = [
            [
            ],
        ];
    }
}