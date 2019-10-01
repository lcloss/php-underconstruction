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

    public function addNotifier($notifier)
    {
        $notifier['sent_time']  = date("Y-m-d H:i:s");
        $notifier['ip_address'] = get_ip();

        $this->setValues($notifier);

        if ( $this->checkEmail( $this->getemail() ) ) {
            $this->flash->addMessageNow('error', 'Email já cadastrado.');
        } else {
            $sql = $this->sql->insert(['name', 'email', 'sent_time', 'ip_address'])->get();
            $values = $this->sql->values([
                'name'          => $this->getname(),
                'email'         => $this->gete_mail(),
                'sent_time'     => $this->getsent_time(),
                'ip_address'    => $this->getip_address()
            ]);

            $this->execute($sql, $values);
                  
            $this->flash->addMessageNow('success', 'Email adicionado à lista');
        }
        
    }
}