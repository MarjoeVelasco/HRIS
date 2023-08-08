<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceSetting;
use App\Models\HybridEmployee;
use DB;



class AttendanceSettingController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'isAdmin']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = AttendanceSetting::select('id','system_setting_name','system_setting_value')->paginate(20);

        $users = DB::table('hybrid_employees')
                ->join('users','hybrid_employees.user_id','=','users.id')
                ->join('employees','employees.employee_id','=','users.id')
                ->select('hybrid_employees.id','users.image','employees.firstname','employees.lastname','employees.position','hybrid_employees.user_id')
                ->get();

        return view('admin.attendancesettings.index')
                ->with('settings',$settings)
                ->with('users',$users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $settings = AttendanceSetting::select('id','system_setting_name','system_setting_value')->where('id', $id)->first();   
        return view('admin.attendancesettings.edit')->with('settings',$settings);
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
        $settings = AttendanceSetting::find($id);
        $settings->system_setting_value = $request->get('system_value');
        $settings->save();

        return redirect('/attendance-setting')->with('success','Settings has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
