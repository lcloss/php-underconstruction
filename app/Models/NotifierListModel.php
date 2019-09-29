<?php

namespace App\Models;

use App\Model;

class NotifierListModel extends Model
{
    protected $table = 'NotifierList';

    public function __construct($db, $flash)
    {
        parent::__construct($db, $this->table, $flash);
    }

    public function selectByEmail($email)
    {
        $sql = $this->sql->select(['*'])->where(['email'])->get();
        $values = $this->sql->values([
            'email' => $email
        ]);

        $res = $this->query($sql, $values);

        if ( count($res) > 0 ) {
            $this->setValues($res[0]);
        } else {
            $this->setid(0);
        }

        return $res;
    }

    public function checkEmail($email)
    {
        $this->selectByEmail($email);

        if ( $this->getid() > 0 ) {
            return true;
        } else {
            return false;
        }
    }

    public function addNotifier($email)
    {
        if ( $this->checkEmail($email) ) {
            $this->flash->addMessageNow('error', 'Email já cadastrado.');
        } else {
            $this->setemail($email);

            $sql = $this->sql->insert(['email'])->get();
            $values = $this->sql->values([
                'name'          => $this->getname(),
                'email'         => $this->getemail(),
                'sent_time'     => date("Y-m-d H:i:s"),
                'ip_address'    => get_ip()
            ]);
            
            $this->execute($sql, $values);
                  
            $this->flash->addMessageNow('success', 'Email adicionado à lista');
        }
        
    }
}