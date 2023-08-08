<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceSetting;
use App\Models\HybridEmployee;
use App\User;
use App\Employee;
use DB;


class HybridEmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        //get list of voters
        $users = DB::table('users')
                ->join('employees','users.id','=','employees.employee_id')
                ->select('employees.employee_id','employees.firstname','employees.lastname')
                ->where('users.is_disabled','=',0)
                ->orderBy('employees.lastname', 'desc')
                ->get();

        return view('admin.hybridemployee.create')->with('users',$users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        HybridEmployee::create([
            'user_id' => $request->get('user'),
        ]);

        return redirect('/attendance-setting')->with('success','Employee successfully added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = DB::table('hybrid_employees')->where('id', '=', $id)->delete();
        if($deleted) {
            return redirect('/attendance-setting')->with('success','User has been removed');
        }
    }
}
