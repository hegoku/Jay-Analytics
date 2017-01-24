<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Project extends Model
{
    protected $connection="mongodb";
    protected $collection = 'project';
}
