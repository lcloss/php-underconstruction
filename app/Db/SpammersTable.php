<?php

namespace App\Db;

class SpammersTable extends \App\MakeTable 
{
   protected $table = 'Spammers';

   public function __construct($db) {
        parent::__construct($db, $this->table);

        $this->columns = [
            'id'            => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'sent_time'     => 'TIMESTAMP NOT NULL',
            'ip_address'    => 'VARCHAR(10) NULL'
        ];

        $this->seed = [
            [
            ],
        ];
    }
}