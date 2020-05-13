<?php

namespace App;

class UpdateEnv
{
    protected $domain;
    protected $user;
    protected $email;
    protected $password;
    protected $dbname;
    protected $dbuser;
    protected $dbpass;

    public function __construct($domain, $user, $email, $password, $dbname, $dbuser, $dbpass)
    {
        $this->domain = $domain;
        $this->user = $user;
        $this->email = $email;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->dbuser = $dbuser;
        $this->dbpass = $dbpass;
    }

    public function updateDomain($domain, $ssl) 
    {
        $make_tpl = file_get_contents('.env');

        if ($ssl == true) {
            $proto = 'https://';
        } else {
            $proto = 'http://';
        }
        $make_tpl = preg_replace('/SITE_URL="http([s]*):\/\/(.*)"/', 'SITE_URL="' . $proto . $domain . '"', $make_tpl);
        file_put_contents('.env', $make_tpl);
    }

    public function update() 
    {
        $make_tpl = file_get_contents('.env');

        $make_tpl = preg_replace('/ADMIN_EMAIL="(.*)"/', 'ADMIN_EMAIL="' . $this->email . '"', $make_tpl);
        $make_tpl = preg_replace('/FROM_EMAIL="(.*)"/', 'FROM_EMAIL="' . $this->email . '"', $make_tpl);
        $make_tpl = preg_replace('/REPLY_TO="(.*)"/', 'REPLY_TO="' . $this->email . '"', $make_tpl);
        $make_tpl = preg_replace('/DB_USER="(.*)"/', 'DB_USER="' . $this->user . '_' . $this->dbuser . '"', $make_tpl);
        $make_tpl = preg_replace('/DB_PASS="(.*)"/', 'DB_PASS="' . $this->dbpass . '"', $make_tpl);
        $make_tpl = preg_replace('/DB_NAME="(.*)"/', 'DB_NAME="' . $this->user . '_' . $this->dbname . '"', $make_tpl);
        $make_tpl = preg_replace('/MAIL_HOST="(.*)"/', 'MAIL_HOST="mail.' . $this->domain . '"', $make_tpl);
        $make_tpl = preg_replace('/MAIL_PORT=(.*)/', 'MAIL_PORT=993' . '', $make_tpl);
        $make_tpl = preg_replace('/MAIL_FROM_ADDR="(.*)"/', 'MAIL_FROM_ADDR="' . $this->email . '"', $make_tpl);
        $make_tpl = preg_replace('/MAIL_REPLY_TO="(.*)"/', 'MAIL_REPLY_TO="' . $this->email . '"', $make_tpl);
        $make_tpl = preg_replace('/MAIL_USERNAME="(.*)"/', 'MAIL_USERNAME="' . $this->email . '"', $make_tpl);
        $make_tpl = preg_replace('/MAIL_PASSWORD="(.*)"/', 'MAIL_PASSWORD="' . $this->password . '"', $make_tpl);
        file_put_contents('.env', $make_tpl);
    }
}