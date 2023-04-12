<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use Validator;
use App\Models\Task;
class TaskController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::select('id','title','description','status','project_id','user_id')->get()->toArray();
        if(isset($tasks)){
            return $this->sendResponse($tasks,"Task fetched successfully."); 
        }else{
            return $this->sendError('Task',['error'=>'Task not available.']);
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
                'title'=>'required',
                'status'=>'required',
                'project_id'=>'required',
                'user_id'=>'required',
            ]);
        if($validator->fails()){
            return $this->sendError('Validator Error',$validator->errors());
        }
        $input = $request->all();
        
        $newTask = Task::create($input);
        if($newTask){  
            return $this->sendResponse($newTask,'Task created Successfully.');     
        }else{
            return $this->sendError('Failed to create Task.');
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
        $task =Task::select('id','title','description','status','project_id','user_id')
        ->where('id',$id)
        ->get()->toArray();
        if (is_null($task)) {
            return $this->sendError('Not found.');
        }else{
            return $this->sendResponse($task,"Details retrieved successfully.");
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
            'title'=>'required',
            'status'=>'required',
            'project_id'=>'required',
            'user_id'=>'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Validator Error',$validator->errors());
        }
        
        $taskUpdate = Task::where('id',$id)->update($input);

        if(isset($taskUpdate)){
           return $this->sendResponse($taskUpdate,"Task updated successfully."); 
        }else{
            return $this->sendError('Task not updated successfully.');
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
        $taskDelete = Task::where('id',$id)->delete();
        if($taskDelete){
            return $this->sendResponse($taskDelete,"Task deleted successfully.");
        }else{
            return $this->sendError('Not deleted',['error'=>'Task not deleted successfully.']);
        }
    }
}
