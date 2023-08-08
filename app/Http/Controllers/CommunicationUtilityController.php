<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Attendance;
use App\User;
use DB;
use App\Exports\CommunicationUtilityExport;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Facades\Excel;


class CommunicationUtilityController extends Controller
{
    public function __construct() {
        $this->middleware(['auth', 'isAdmin']); //isAdmin middleware lets only users with a //specific permission permission to access these resources
        
    }

    public function index(request $request) {
        
        return view('admin.utilityreport.index');
    }

    public function export(request $request)
    {
        $division = $request->get('division');
        $temp = explode("(", $division);
        $division_short = str_replace(")", "", $temp[1]);

        $today = date("Y-m-d H:i:s");
        //get month and year
        $month_year = $request->input('month_year');
        //get first and last day
        $date = date('Y-m-d', strtotime($month_year));
        $from = date('Y-m-01', strtotime($date));
        $to = date('Y-m-t', strtotime($date));



        

        return Excel::download(new CommunicationUtilityExport($division, $from, $to,$month_year), $month_year . '_' . $division_short . '_Communication Utility Report.xlsx');
        
        

    }



}
