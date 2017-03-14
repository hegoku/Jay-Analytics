<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Page extends Model
{
    protected $connection="mongodb";
    protected $collection = 'page';
    public $timestamps=false;
}
