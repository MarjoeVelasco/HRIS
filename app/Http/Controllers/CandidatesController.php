<?php

namespace App\Http\Controllers;

use App\Candidates;
use Illuminate\Http\Request;
use App\Voters;
use App\Employee;
use App\Categories;
use App\User;
use App\Forms;
use DB;
use App\ErrorLog;

class CandidatesController extends Controller
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
    
        $candidates = DB::table('candidates')
                    ->join('users','candidates.user_id','=','users.id')
                    ->join('employees','candidates.user_id','=','employees.employee_id')
                    ->join('forms','candidates.form_id','=','forms.id')
                    ->join('categories','candidates.category_id','=','categories.id') 
                    ->select('candidates.id','users.image','employees.firstname','employees.lastname','forms.title as form_title','categories.title as category_title')
                    ->orderBy('categories.id','ASC')
                    ->where('forms.is_archive','0')
                    ->paginate(20);
        



        return view('admin.candidates.index')
                ->with('candidates',$candidates);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        $forms = Forms::select('id','title')
                    ->where('status',0)
                    ->where('is_archive',0)
                    ->get();

        $categories = Categories::get();
        $employees = DB::table('employees')
                    ->join('users','employees.employee_id','=','users.id') 
                    ->select('users.id','employees.firstname','employees.lastname')
                    ->where('users.is_disabled',0)
                    ->orderBy('lastname','ASC')
                    ->get();

        return view('admin.candidates.create',['categories' => $categories,
                                               'employees' => $employees,
                                               'forms' => $forms  ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $categories = $request['category']; 

        if (isset($categories)) {
            foreach ($categories as $category) {
                Candidates::create([
                    'user_id' => $request->get('user'),
                    'form_id' => $request->get('form'),
                    'category_id' => $category
                ]);
            }
        }

        return back()->with('success','Candidate Added');


        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\candidates  $candidates
     * @return \Illuminate\Http\Response
     */
    public function show(candidates $candidates)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\candidates  $candidates
     * @return \Illuminate\Http\Response
     */
    public function edit(candidates $candidates)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\candidates  $candidates
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, candidates $candidates)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\candidates  $candidates
     * @return \Illuminate\Http\Response
     */
    public function destroy(candidates $candidates) {
        //
    }

    public function deleteCandidate(Request $request) {

        $deleted = DB::table('candidates')->where('id', '=', $request->get('id'))->delete();

        return back()->with('success', 'Voter Removed');
    }
}
