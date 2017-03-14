<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class SummaryPVHour extends Model
{
    protected $connection="mongodb";
    protected $collection = 'summary_pv_hour';
}
