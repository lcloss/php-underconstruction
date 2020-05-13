<?php

namespace App\Db;

class ContactsTable extends \App\MakeTable 
{
   protected $table = 'Contacts';

   public function __construct($db) {
        parent::__construct($db, $this->table);

        $this->columns = [
            'id'            => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'name'          => 'VARCHAR(50) NOT NULL',
            'email'         => 'VARCHAR(120) NOT NULL',
            'subject'       => 'VARCHAR(50) NOT NULL',
            'message'       => 'TEXT NOT NULL',
            'sent_time'     => 'DATETIME NOT NULL',
            'ip_address'    => 'VARCHAR(32) NULL'
        ];

        $this->seed = [
            [
            ],
        ];
    }
}