<?php

namespace App\Http\Controllers;
use DateTime;
use URL;
use Auth;
use Mail;
use DB;
use Schema;
use Session;
use App\Model;
use App\Task;
use App\Report;
use App\Employee;
use App\Project;
use App\CfgActivity;
use App\CfgDesignations;
use App\CfgTaskStatus;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public $excludedColumns=array('created_at','updated_at','deleted_at');
    public $readOnlyColumns=array('id','created_at','updated_at','deleted_at');
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tables=config('configTable');
        $tableName='';
        return view('admin.configTable.view',compact('tables','tableName'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        if($request->table!='')
        {
            $tableName=base64_decode($request->table);
            
            $modelName="App\\".config('configTable')[$tableName]['model'];
            $tableRow=new $modelName;
            
            $tableColumns = array_diff(Schema::getColumnListing($tableName),$this->readOnlyColumns);

            return view('admin.configTable.create',compact('tableName','tableColumns','tableRow'));
        }

        return redirect('/Admin/ConfigTable');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if($request->table!=''){
            foreach ($request->fields as $key => $value) {
                if($value=='id')
                {
                    continue;
                }
                $row[$value]=$request->data[$key];
            }
            $tableName=$request->table;
            $modelName="App\\".config('configTable')[$tableName]['model'];
            $result=$modelName::create($row);
            // dd($tableName,$modelName,$result);
            if($result)
            {  
                return ['status'=>true];
            }else
            {
                return ['status'=>false];
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $tables=config('configTable');
        if($request->table!='')
        {

            $tableName=base64_decode($request->table);
            $modelName="App\\".config('configTable')[$tableName]['model'];
            if($modelName=="App\Project")
            {
                 $tableRows=$modelName::orderBy('id','desc')->where('status','!=','closed')->get();
            }
            else
            {
                 $tableRows=$modelName::orderBy('id','desc')->get();
            }
            // dd($tableRows[0]['id']);
            $tables[$tableName]['columns']=array_diff(Schema::getColumnListing($tableName),$this->excludedColumns);

        }

        return view('admin.configTable.view',compact('tableRows','tables','tableName'));
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {

        if($request->table!=''&&$request->id!='')
        {
            $tableName=base64_decode($request->table);
            $tableColumns = array_diff(Schema::getColumnListing($tableName),$this->readOnlyColumns);
            $modelName="App\\".config('configTable')[$tableName]['model'];
            $tableRow=$modelName::find($request->id);

            return view('admin.configTable.edit',compact('tableName','tableColumns','tableRow'));
        }

        return redirect('/Admin/ConfigTable');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // dd($request->all(),$task);
        if($request->table!=''){
            $tableName=$request->table;
            foreach ($request->fields as $key => $value) {
                $row[$value]=$request->data[$key];
            }

            
            $modelName="App\\".config('configTable')[$tableName]['model'];
            $fetchRow=$modelName::find($row['id']);
            $result=$fetchRow->update($row);
            if($result)
            {  
                return ['status'=>true];
            }else
            {
                return ['status'=>false];
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if($request->table!='')
        {  
            $tableName=$request->table;
            foreach ($request->fields as $key => $value) {
                $row[$value]=$request->data[$key];
            }
            $modelName="App\\".config('configTable')[$tableName]['model'];
            $fetchRow=$modelName::find($row['id']);
            $result=$fetchRow->delete();
            if($result)
            {  
                return ['status'=>true];
            }else
            {
                return ['status'=>false];
            }
        }
    }
}
