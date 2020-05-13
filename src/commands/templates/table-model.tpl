<?php

namespace App\Models;

use App\Model;

class {% tablename %}Model extends Model
{
    protected $table = '{% tablename %}';

    public function __construct($db, $flash)
    {
        parent::__construct($db, $this->table, $flash);
    }
}