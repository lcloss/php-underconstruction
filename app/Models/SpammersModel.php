<?php

namespace App\Models;

use App\Model;

class SpammersModel extends Model
{
    protected $table = 'Spammers';

    public function __construct($db, $flash)
    {
        parent::__construct($db, $this->table, $flash);
    }

    public function addSpammer() 
    {
        $sql = $this->sql->insert(['sent_time', 'ip_address'])->get();
        $values = $this->sql->values([
            'sent_time'     => date("Y-m-d H:i:s"),
            'ip_address'    => get_ip()
        ]);
        
        $this->execute($sql, $values);
              
        // $this->flash->addMessageNow('success', 'Contato registado.');
    }

    public function selectByIpAddress($ip_address)
    {
        $sql = $this->sql->select(['*'])->where(['ip_address'])->get();
        $values = $this->sql->values([
            'ip_address' => $ip_address
        ]);

        $res = $this->query($sql, $values);

        if ( count($res) > 0 ) {
            $this->setValues($res[0]);
        } else {
            $this->setid(0);
        }

        return $res;
    }

    public function checkSpammer() 
    {
        $ip_address = get_ip();

        $this->selectByIpAddress($ip_address);

        if ( $this->getid() > 0 ) {
            return true;
        } else {
            return false;
        }
    }
}