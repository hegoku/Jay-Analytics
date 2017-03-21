<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class SummaryPVMonth extends Model
{
    protected $connection="mongodb";
    protected $collection = 'summary_pv_month';
}
