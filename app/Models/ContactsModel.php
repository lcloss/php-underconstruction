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
        $contact['sent_time'] = date("Y-m-d H:i:s");
        $contact['ip_address'] = get_ip();

        $this->setValues($contact);

        $sql = $this->sql->insert(['name', 'email', 'subject', 'message', 'sent_time', 'ip_address'])->get();
        $values = $this->sql->values([
            'name'          => $this->getname(),
            'email'         => $this->getemail(),
            'subject'       => $this->getsubject(),
            'message'       => $this->getmessage(),
            'sent_time'     => $this->getsent_time(),
            'ip_address'    => $this->getip_address()
        ]);
        
        $this->execute($sql, $values);
              
        $this->flash->addMessageNow('success', 'Contato registado.');

    }
}