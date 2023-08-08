<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Attendance;
use App\ErrorLog;
use App\WorkSetting;
use DB;




class WorkSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isAdmin']); //isAdmin middleware lets only users with a //specific permission permission to access these resources
    }

    //switch work setting

    public function switchWorkSetting($id)
    {
        DB::beginTransaction();
        try {
        

            $work_setting = WorkSetting::where("attendance_id",$id)->first();

            $current=$work_setting->status;
            $new_work_setting="";
            if($current=="work from home")
            {
                $new_work_setting="in office";
            }

            else
            {
                $new_work_setting="work from home";
            }


            WorkSetting::where("attendance_id",$id)
            ->update(["status" => $new_work_setting]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $log = new ErrorLog();
            $log->message = $e->getMessage();
            $log->file = $e->getFile();
            $log->line = $e->getLine();
            $log->url = $_SERVER['REQUEST_URI'];
            $log->save();
            return back()->with('error','Execution Error. Record Not Saved! Please contact the administrator');
        }
        return back()->with('success', 'Work setting changed!');
    }

    public function assignWorkSetting($id,$worksetting)
    {
        DB::beginTransaction();
        try {
    
            WorkSetting::create([
                'attendance_id' => $id,
                'status' => $worksetting,
                ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $log = new ErrorLog();
            $log->message = $e->getMessage();
            $log->file = $e->getFile();
            $log->line = $e->getLine();
            $log->url = $_SERVER['REQUEST_URI'];
            $log->save();
            return back()->with('error','Execution Error. Record Not Saved! Please contact the administrator');
        }
        return back()->with('success', 'Work setting changed!');
    }




}
