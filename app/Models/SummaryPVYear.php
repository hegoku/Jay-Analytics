<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class SummaryPVYear extends Model
{
    protected $connection="mongodb";
    protected $collection = 'summary_pv_year';
}
