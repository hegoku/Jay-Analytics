<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Respone;
use App\Models\Project;
use App\Http\Controllers\Controller;
use App\Mongodb;
use App\Library\DateFormat;

class PageController extends Controller
{
    public function page(Project $project,Request $request)
    {
        $start_time=DateFormat::string2int($request->input('start_time'), $request->input('date_type', 'day'));
        $end_time=DateFormat::string2int($request->input('end_time'), $request->input('date_type', 'day'));
        $cursor=$this->getPV($request->input('date_type'), $project, $start_time, $end_time);
        $result=[];
        foreach ($cursor as $value) {
            $value->create_time=DateFormat::int2string($value->create_time);
            array_push($result, $value);
        }
        return response()->json(['code'=>200, 'data'=>$result]);
    }

    protected function getPV($date, $project, $start_time, $end_time)
    {
        $filter=[
            'project_id'=>new \MongoDB\BSON\ObjectID($project->_id)
        ];
        
        if ($start_time==$end_time) {
            $filter['create_time']=(int)$start_time;
        } else {
            $filter['create_time']=[
                '$gte'=>(int)$start_time,
                '$lte'=>(int)$end_time
            ];
        }
        return Mongodb::getInstance()->collection('summary_'.$date)->query(
            $filter,
            ['projection'=>['_id'=>0, 'project_id'=>0]]
        );
    }
}
