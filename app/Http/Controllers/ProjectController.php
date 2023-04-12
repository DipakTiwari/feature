<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use Validator;
use App\Models\Project;

class ProjectController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::select('id','name')->get()->toArray();
        if(isset($projects)){
            return $this->sendResponse($projects,"Projects fetched successfully."); 
        }else{
            return $this->sendError('Projects',['error'=>'Projects not available.']);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
                'name'=>'required|unique:projects',
            ]);
        if($validator->fails()){
            return $this->sendError('Validator Error',$validator->errors());
        }
        $input = $request->all();
        
        $newProject = Project::create($input);
        if($newProject){  
            return $this->sendResponse($newProject,'Project created Successfully.');     
        }else{
            return $this->sendError('Failed to create Project.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project =Project::select('id','name')
        ->where('id',$id)
        ->get()->toArray();
        if (is_null($project)) {
            return $this->sendError('Not found.');
        }else{
            return $this->sendResponse($project,"Details retrieved successfully.");
        }  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:projects,name,'.$id,
        ]);
        if($validator->fails()){
            return $this->sendError('Validator Error',$validator->errors());
        }
        
        $projectUpdate = Project::where('id',$id)->update($input);

        if(isset($projectUpdate)){
           return $this->sendResponse($projectUpdate,"Project updated successfully."); 
        }else{
            return $this->sendError('Project not updated successfully.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $projectDelete = Project::where('id',$id)->delete();
        if($projectDelete){
            return $this->sendResponse($projectDelete,"Project deleted successfully.");
        }else{
            return $this->sendError('Not deleted',['error'=>'Project not deleted successfully.']);
        }
    }
}
