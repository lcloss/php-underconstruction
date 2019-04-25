<?php

namespace App\Models;

use App\Model;

class ContactsModel extends Model
{
    protected $table = 'Contacts';

    public function __construct($db, $flash)
    {
        parent::__construct($db, $this->table, $flash);
    }

    public function addContact($contact)
    {
        $this->setValues($contact);

        $sql = $this->sql->insert(['name', 'email', 'subject', 'message'])->get();
        $values = $this->sql->values([
            'name'  => $this->getname(),
            'email' => $this->getemail(),
            'subject'   => $this->getsubject(),
            'message'   => $this->getmessage()
        ]);
        
        $this->execute($sql, $values);
              
        $this->flash->addMessageNow('success', 'Contato registado.');

    }
}