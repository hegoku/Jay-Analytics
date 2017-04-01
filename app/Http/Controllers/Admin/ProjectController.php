<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Respone;
use App\Models\Project;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    public function add(Request $request)
    {
        $project=new Project();
        $project->name=$request->input('name');
        $project->save();
        return response()->json(['code'=>200]);
    }

    public function list1()
    {
        $data=Project::all();
        return response()->json(['code'=>200,'data'=>$data]);
    }
    
    public function index()
    {
        return view('admin.index');
    }
}
