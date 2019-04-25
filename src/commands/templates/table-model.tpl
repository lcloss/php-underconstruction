<?php

namespace App\Models;

use App\Model;

class {% tablename %}Model extends Model
{
    protected $table = '{% tablename %}';

    public function __construct($db)
    {
        parent::__construct($db, $this->table);
    }
}