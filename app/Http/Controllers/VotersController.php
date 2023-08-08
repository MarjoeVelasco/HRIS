<?php

namespace App\Http\Controllers;

use App\Voters;
use App\Employee;
use App\User;
use App\Forms;
use DB;
use App\ErrorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class VotersController extends Controller
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
    public function index() {

        
        //get list of voters
        $voters = DB::table('voters')
                    ->join('employees','voters.user_id','=','employees.employee_id')
                    ->join('users','employees.employee_id','=','users.id')
                    ->join('forms','voters.form_id','=','forms.id') 
                    ->select('users.image','employees.firstname','employees.lastname','voters.ballot_number','voters.id','forms.title','forms.status')
                    //->where('voters.form_id','=',$forms->id)
                    ->get();

        return view('admin.voters.index')
                ->with('voters',$voters);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $ballot_number = $this->generateReference();
        
        $employees = DB::table('employees')
                    ->join('users','employees.employee_id','=','users.id') 
                    ->select('users.id','employees.firstname','employees.lastname')
                    ->where('users.is_disabled',0)
                    ->orderBy('lastname','ASC')
                    ->get();

        $forms = Forms::select('id','title')
                ->where('is_archive',0)
                ->get();

        return view('admin.voters.create')
            ->with('ballot_number',$ballot_number)
            ->with('employees',$employees)
            ->with('forms',$forms);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {


        //validate request
        $this->validate($request, [
            'user' => 'required',
            'form' => 'required',
            'ballot_number' => 'required|unique:voters',
        ]);

        Voters::create([
            'form_id' => $request->get('form'),
            'ballot_number' => $request->input('ballot_number'),
            'user_id' => $request->get( 'user'),
        ]);

        return redirect('/voters')->with('success','Voter has been added');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\voters  $voters
     * @return \Illuminate\Http\Response
     */
    public function show(voters $voters)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\voters  $voters
     * @return \Illuminate\Http\Response
     */
    public function edit(voters $voters)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\voters  $voters
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\voters  $voters
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        //


    }

    private function generateReference() {
        $number = mt_rand(10000, 99999);

        if ($this->referenceExists($number)) {
            return $this->generateReference();
        }
    
        return $number;    
    }

    private function referenceExists($number) { 
        // query the database and return a boolean
        return Voters::where('ballot_number',$number)->exists();
    }

    private function disseminateBallot() {

    }

    public function deleteVoter(Request $request) {

        $deleted = DB::table('voters')->where('id', '=', $request->get('id'))->delete();

        return back()->with('success', 'Candidate Removed');
    }

}
