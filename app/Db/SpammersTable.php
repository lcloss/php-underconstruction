<?php

namespace App\Db;

class SpammersTable extends \App\MakeTable 
{
   protected $table = 'Spammers';

   public function __construct($db) {
        parent::__construct($db, $this->table);

        $this->columns = [
            'id'            => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'email1'        => 'VARCHAR(120) NULL',
            'email2'        => 'VARCHAR(120) NULL',
            'points'        => 'DECIMAL(3,2) NULL',
            'sent_time'     => 'DATETIME NOT NULL',
            'ip_address'    => 'VARCHAR(32) NULL'
        ];

        $this->seed = [
            [
            ],
        ];
    }
}