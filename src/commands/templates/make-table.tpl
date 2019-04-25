<?php

namespace App\Db;

class {% tablename %}Table extends \App\MakeTable 
{
   protected $table = '{% tablename %}';

   public function __construct($db) {
        parent::__construct($db);

        $this->columns = [
            'id'        => 'INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
            'column1'   => 'VARCHAR(10) NOT NULL',
            'column2'   => 'VARCHAR(10) NOT NULL',
        ];

        $this->seed = [
            [
                'column1'   => 'Data 1',
                'column2'   => 'Data 2',
            ],
        ];
    }
}