<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Voters;
use App\Ballots;
use App\Employee;
use App\User;
use App\Forms;
use DB;

use App\ErrorLog;

class ElectionDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isElectionCommittee']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function home() {

        //get active form
        $forms = Forms::select('id','title')->where('status',1)->first();   
        $title = "No Active Forms"; 
        if ($forms!=null) {
            $title = $forms->title; 
        }

        $voted = Ballots::select('ballot_number')->distinct('ballot_number')->where('form_id',$forms->id)->count();
        $number_voters = Voters::where('form_id',$forms->id)->count();

        $yes = round(($voted/$number_voters)*100);
        $not_yet = $number_voters-$voted;

        $voters = DB::table('voters')
                ->join('employees','voters.user_id','=','employees.employee_id')
                ->join('users','employees.employee_id','=','users.id')
                ->join('forms','voters.form_id','=','forms.id')
                ->leftjoin('ballots','voters.ballot_number','=','ballots.ballot_number') 
                ->select('users.image','employees.firstname','employees.lastname','voters.ballot_number','voters.id','forms.title','forms.status','ballots.ballot_number as vote_casted')
                ->distinct('ballot_number')
                ->where('forms.status','=',1)
                ->get();
        
        return view('admin.election.index',compact('title','voters','voted','number_voters','yes','not_yet'));
    }

    public function votersPercentage() {
        $forms = Forms::select('id')->where('status',1)->first();

        $voted = Ballots::select('ballot_number')->distinct('ballot_number')->where('form_id',$forms->id)->count();

        $voters = Voters::where('form_id',$forms->id)->count();

        $yes = ($voted/$voters)*100;
        $no = 100 - $yes;
        return response()->json([
            'voted' => round($yes),
            'not_voted' => round($no),
        ]);
    }

    public function votersPosition() {

        $forms = Forms::select('id')->where('status',1)->first();

        $label = ['President','Vice President','Secretary','Bookkeeper','Auditor','Public Relations Officer','First Level','Second Level'];

        $president = DB::table('ballots')
                    ->join('categories','ballots.category_id','categories.id')
                    ->join('forms','ballots.form_id','forms.id')
                    ->where('ballots.form_id',$forms->id)
                    ->where('categories.title','President')
                    ->count();
        
        $vice_president = DB::table('ballots')
                    ->join('categories','ballots.category_id','categories.id')
                    ->join('forms','ballots.form_id','forms.id')
                    ->where('ballots.form_id',$forms->id)
                    ->where('categories.title','Vice-President')
                    ->count();

        $secretary = DB::table('ballots')
                    ->join('categories','ballots.category_id','categories.id')
                    ->join('forms','ballots.form_id','forms.id')
                    ->where('ballots.form_id',$forms->id)
                    ->where('categories.title','Secretary')
                    ->count();

        $treasurer = DB::table('ballots')
                    ->join('categories','ballots.category_id','categories.id')
                    ->join('forms','ballots.form_id','forms.id')
                    ->where('ballots.form_id',$forms->id)
                    ->where('categories.title','Treasurer')
                    ->count();


        $bookkeeper = DB::table('ballots')
                    ->join('categories','ballots.category_id','categories.id')
                    ->join('forms','ballots.form_id','forms.id')
                    ->where('ballots.form_id',$forms->id)
                    ->where('categories.title','Bookkeeper')
                    ->count();

        $auditor = DB::table('ballots')
                    ->join('categories','ballots.category_id','categories.id')
                    ->join('forms','ballots.form_id','forms.id')
                    ->where('ballots.form_id',$forms->id)
                    ->where('categories.title','Auditor')
                    ->count();


        $pro = DB::table('ballots')
                    ->join('categories','ballots.category_id','categories.id')
                    ->join('forms','ballots.form_id','forms.id')
                    ->where('ballots.form_id',$forms->id)
                    ->where('categories.title','Public Relations Officer')
                    ->count();

        $first_level = DB::table('ballots')
                    ->join('categories','ballots.category_id','categories.id')
                    ->join('forms','ballots.form_id','forms.id')
                    ->where('ballots.form_id',$forms->id)
                    ->where('categories.title','First-Level Representative')
                    ->count();

        $second_level = DB::table('ballots')
                    ->join('categories','ballots.category_id','categories.id')
                    ->join('forms','ballots.form_id','forms.id')
                    ->where('ballots.form_id',$forms->id)
                    ->where('categories.title','Second-Level Representative')
                    ->count();

        return response()->json([
            'label' => $label,
            'president' => $president,
            'vice_president' => $vice_president,
            'sec' => $secretary,
            'treasurer' => $treasurer,
            'bookkeeper' => $bookkeeper,
            'auditor' => $auditor,
            'pro' => $pro,
            'first_level' => $first_level,
            'second_level' => $second_level,
        ]);
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
        //
    }
}
