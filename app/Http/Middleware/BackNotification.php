<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

use App\ErrorLog;
use App\FiledLeaves;
use App\FiledCto;

class BackNotification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        

        //get supervisor leave approval
        $supervisor_leave = FiledLeaves::where('supervisor_id',auth()->user()->id)
                ->where('status','Routed to Supervisor')
                ->count();

        //get supervisor cto approval        
        $supervisor_cto = FiledCto::where('supervisor_id',auth()->user()->id)
                ->where('status','Routed to Supervisor')
                ->count();

        $leave_supervisor_approval = $supervisor_cto + $supervisor_leave;

                //get number of pending leaves and cto for HR action
        $hr_leave = FiledLeaves::where('hr_id',auth()->user()->id)
                ->where('status','Pending')
                ->orWhere('status','Processing')
                ->orWhere('status','Routed to HR')
                ->count();

        $hr_cto = FiledCto::where('hr_id',auth()->user()->id)
                ->where('status','Pending')
                ->orWhere('status','Processing')
                ->orWhere('status','Routed to HR')
                ->count();
        
        $leave_hr_approval = $hr_leave + $hr_cto;

        View::share([
            'leave_supervisor_approval_notif' => $leave_supervisor_approval,
            'supervisor_cto_approval_notif' => $supervisor_cto,
            'supervisor_leave_approval_notif' => $supervisor_leave,

            'leave_hr_approval_notif' => $leave_hr_approval,
            'hr_cto_approval_notif' => $hr_cto,
            'hr_leave_approval_notif' => $hr_leave,
        ]);

        return $next($request);
    }
}
