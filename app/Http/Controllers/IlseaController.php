<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Voters;
use App\Employee;
use App\User;
use App\Categories;
use App\Ballots;
use App\Forms;
use DB;
use App\ErrorLog;
use App\Events\VotesUpdated;

class IlseaController extends Controller {
    
    /*
    public function __construct() {
        $this->middleware(['auth', 'isElectionCommittee']); 
    }*/

    public function resolution() {

        $form = Forms::select('id')->where('status',1)->first();
        return view('users.ilsea.resolution')->with('form',$form);
    }



    public function submitVote(Request $request) {
        
        /*
        $validatedData = $request->validate([
            'president' =>      ['required','different:vice_president','different:secretary','different:treasurer','different:bookkeeper','different:auditor','different:pro','different:first_level','different:second_level'],
            'vice_president' => ['required','different:president','different:secretary','different:treasurer','different:bookkeeper','different:auditor','different:pro','different:first_level','different:second_level'],
            'secretary' =>      ['required','different:president','different:vice_president','different:treasurer','different:bookkeeper','different:auditor','different:pro','different:first_level','different:second_level'],
            'treasurer' =>      ['required','different:president','different:vice_president','different:secretary','different:bookkeeper','different:auditor','different:pro','different:first_level','different:second_level'],
            'bookkeeper' =>     ['required','different:president','different:vice_president','different:secretary','different:treasurer','different:auditor','different:pro','different:first_level','different:second_level'],
            'auditor' =>        ['required','different:president','different:vice_president','different:secretary','different:treasurer','different:bookkeeper','different:pro','different:first_level','different:second_level'],
            'pro' =>            ['required','different:president','different:vice_president','different:secretary','different:treasurer','different:bookkeeper','different:auditor','different:first_level','different:second_level'],
            'first_level' =>    ['required','different:president','different:vice_president','different:secretary','different:treasurer','different:bookkeeper','different:auditor','different:pro','different:second_level'],
            'second_level' =>   ['required','different:president','different:vice_president','different:secretary','different:treasurer','different:bookkeeper','different:auditor','different:pro','different:first_level'],
        ]);
        */

        $validatedData = $request->validate([
            'treasurer' =>      ['required'],
        ]);
    

        /*
        $validatedData = $request->validate([
            'second_level_alternate' => ['required'],
            'codi_supervisory' => ['required'],
            'codi_rank_and_file' => ['required']
        ]);*/

        $reference_number = $this->generateReference();
        
        //get current active form
        $form = Forms::select('id')->where('status',1)->first();

        //get ballot number
        $voter = Voters::where('user_id',auth()->user()->id)->where('form_id',$form->id)->select('ballot_number')->first();
		
		//check if voted
        if(Ballots::where('form_id',$form->id)->where('ballot_number',$voter->ballot_number)->exists()) {
            return redirect('/vote-confirmation')->with('success','You have already voted');
        }

        //treasurer
        Ballots::create([
            'reference_number' => $reference_number,
            'ballot_number' => $voter->ballot_number,
            'form_id' => $form->id,
            'category_id' => $request->input( 'treasurer_id'),
            'user_id' => $request->get( 'treasurer'),
        ]);

        /*
        //ILSEA second level rep alternate
        $ballots = Ballots::create([
            'reference_number' => $reference_number,
            'ballot_number' => $voter->ballot_number,
            'form_id' => $form->id,
            'category_id' => $request->input( 'second_level_alternate_id'),
            'user_id' => $request->get( 'second_level_alternate'),
        ]);

        //CODI supervisory
        $ballots = Ballots::create([
            'reference_number' => $reference_number,
            'ballot_number' => $voter->ballot_number,
            'form_id' => $form->id,
            'category_id' => $request->input( 'codi_supervisory_id'),
            'user_id' => $request->get( 'codi_supervisory'),
        ]);

        //CODI rank and file
        $ballots = Ballots::create([
            'reference_number' => $reference_number,
            'ballot_number' => $voter->ballot_number,
            'form_id' => $form->id,
            'category_id' => $request->input( 'codi_rank_and_file_id'),
            'user_id' => $request->get( 'codi_rank_and_file'),
        ]);*/

        

        


        return redirect('/vote-confirmation')->with('success','Voter has been added');
        

    }

    public function confirmation() {

        //get ballot number and reference_number and date_time
        $ballots = DB::table('ballots')
                ->join('voters','ballots.ballot_number','=','voters.ballot_number')
                ->where('voters.user_id',auth()->user()->id)
                ->select('ballots.reference_number','ballots.created_at','ballots.form_id')
                ->limit(1)
                ->get();
        

        return view('users.ilsea.confirmation')->with('ballots',$ballots);
    }

    public function results($id) {

        //form disabled
        if(Forms::where('status',0)->where('id',$id)->exists()) {
            return redirect('/home')->with('error','form not found');
        }

        $forms = Forms::select('title')->where('id',$id)->first();
        $election_title=$forms->title;

        $current_date = date('F d, Y');

        //categories
        $treasurer = Categories::where('title','Treasurer (Special Elections July 2023)')->select('id')->first();
        /*
        $pres = Categories::where('title','President')->select('id')->first();
        $second_level_alternate = Categories::where('title','Second-Level Representative (Special Elections June 2023)')->select('id')->first();
        $codi_supervisory = Categories::where('title','CODI Supervisory')->select('id')->first();
        $codi_rank_and_file = Categories::where('title','CODI Rank and file')->select('id')->first();
        */

        //get results for president
        $treasurer_results = DB::table('ballots')
                    ->join('employees','ballots.user_id','=','employees.employee_id')
                    ->join('users','ballots.user_id','=','users.id')
                    ->select('employees.firstname','employees.lastname','users.image',DB::raw('ballots.user_id, count(user_id) AS occurrences, round(count(user_id)/(select count(*) from ballots where category_id=13) * 100) as percent, RANK() OVER (ORDER BY occurrences desc) as rank'))
                    ->where('ballots.category_id',$treasurer->id)
                    ->groupBy('ballots.user_id','employees.firstname','employees.lastname','users.image')
                    ->orderBy('occurrences', 'DESC')
                    ->limit(5)
                    ->get();

        /*
        //get results for second level rep
        $second_level_alternate_results = DB::table('ballots')
                    ->join('employees','ballots.user_id','=','employees.employee_id')
                    ->join('users','ballots.user_id','=','users.id')
                    ->select('employees.firstname','employees.lastname','users.image',DB::raw('ballots.user_id, count(user_id) AS occurrences, round(count(user_id)/(select count(*) from ballots where category_id=10) * 100) as percent, RANK() OVER (ORDER BY occurrences desc) as rank'))
                    ->where('ballots.category_id',$second_level_alternate->id)
                    ->groupBy('ballots.user_id','employees.firstname','employees.lastname','users.image')
                    ->orderBy('occurrences', 'DESC')
                    ->limit(5)
                    ->get();

        //get results for second level rep
        $codi_supervisory_results = DB::table('ballots')
                    ->join('employees','ballots.user_id','=','employees.employee_id')
                    ->join('users','ballots.user_id','=','users.id')
                    ->select('employees.firstname','employees.lastname','users.image',DB::raw('ballots.user_id, count(user_id) AS occurrences, round(count(user_id)/(select count(*) from ballots where category_id=11) * 100) as percent, RANK() OVER (ORDER BY occurrences desc) as rank'))
                    ->where('ballots.category_id',$codi_supervisory->id)
                    ->groupBy('ballots.user_id','employees.firstname','employees.lastname','users.image')
                    ->orderBy('occurrences', 'DESC')
                    ->limit(5)
                    ->get();

        //get results for second level rep
        $codi_rank_and_file_results = DB::table('ballots')
                    ->join('employees','ballots.user_id','=','employees.employee_id')
                    ->join('users','ballots.user_id','=','users.id')
                    ->select('employees.firstname','employees.lastname','users.image',DB::raw('ballots.user_id, count(user_id) AS occurrences, round(count(user_id)/(select count(*) from ballots where category_id=12) * 100) as percent, RANK() OVER (ORDER BY occurrences desc) as rank'))
                    ->where('ballots.category_id',$codi_rank_and_file->id)
                    ->groupBy('ballots.user_id','employees.firstname','employees.lastname','users.image')
                    ->orderBy('occurrences', 'DESC')
                    ->limit(5)
                    ->get();
        */
                            
        return view('users.ilsea.results',compact('election_title','current_date',
                'treasurer_results','treasurer'));
    }


    public function vote($id) {

        //form disabled
        if(Forms::where('status',0)->where('id',$id)->exists()) {
            return redirect('/home')->with('error','form not found');
        }
        
        //get ballot number
        $voters = Voters::select('ballot_number')
                        ->where('form_id',$id)
                        ->where('user_id',auth()->user()->id)
                        ->first();
        
        //check if voted
        if(Ballots::where('form_id',$id)->where('ballot_number',$voters->ballot_number)->exists()) {
            return redirect('/vote-confirmation')->with('success','You have already voted');
        }

        //get form
        $forms = Forms::select('title','description')->where('id',$id)->first();

        //categories
        /*
        $second_level_alternate = Categories::where('title','Second-Level Representative (Special Elections June 2023)')->select('id','title','description')->first();
        $codi_supervisory = Categories::where('title','CODI Supervisory')->select('id','title','description')->first();
        $codi_rank_and_file = Categories::where('title','CODI Rank and file')->select('id','title','description')->first();*/
        $treasurer = Categories::where('title','Treasurer (Special Elections July 2023)')->select('id','title','description')->first();
        //candidates
        
        $treasurer_candidate = DB::table('candidates')
                          ->join('users','candidates.user_id','=','users.id')
                          ->join('employees','users.id','=','employees.employee_id')
                          ->join('categories','candidates.category_id','=','categories.id')
                          ->join('forms','candidates.form_id','=','forms.id')
                          ->select('users.image','employees.firstname','employees.lastname','users.id')
                          ->where('candidates.category_id',$treasurer->id)
                          ->where('candidates.form_id',$id)
                          ->orderBy('employees.lastname','ASC')
                          ->get();
        /*
        $second_level_alternate_candidate = DB::table('candidates')
                          ->join('users','candidates.user_id','=','users.id')
                          ->join('employees','users.id','=','employees.employee_id')
                          ->join('categories','candidates.category_id','=','categories.id')
                          ->join('forms','candidates.form_id','=','forms.id')
                          ->select('users.image','employees.firstname','employees.lastname','users.id')
                          ->where('candidates.category_id',$second_level_alternate->id)
                          ->where('candidates.form_id',$id)
                          ->orderBy('employees.lastname','ASC')
                          ->get();

        $codi_supervisory_candidate = DB::table('candidates')
                          ->join('users','candidates.user_id','=','users.id')
                          ->join('employees','users.id','=','employees.employee_id')
                          ->join('categories','candidates.category_id','=','categories.id')
                          ->join('forms','candidates.form_id','=','forms.id')
                          ->select('users.image','employees.firstname','employees.lastname','users.id')
                          ->where('candidates.category_id',$codi_supervisory->id)
                          ->where('candidates.form_id',$id)
                          ->orderBy('employees.lastname','ASC')
                          ->get();

        $codi_rank_and_file_candidate = DB::table('candidates')
                          ->join('users','candidates.user_id','=','users.id')
                          ->join('employees','users.id','=','employees.employee_id')
                          ->join('categories','candidates.category_id','=','categories.id')
                          ->join('forms','candidates.form_id','=','forms.id')
                          ->select('users.image','employees.firstname','employees.lastname','users.id')
                          ->where('candidates.category_id',$codi_rank_and_file->id)
                          ->where('candidates.form_id',$id)
                          ->orderBy('employees.lastname','ASC')
                          ->get();
            */
    
        return view('users.ilsea.vote',compact('voters','forms','treasurer',
                                        'treasurer_candidate'));
    }

    private function generateReference() {
        $number = mt_rand(10000000, 99999999);

        if ($this->referenceExists($number)) {
            return $this->generateReference();
        }
    
        return $number;    
    }

    private function referenceExists($number) { 
        // query the database and return a boolean
        return Ballots::where('reference_number',$number)->exists();
    }

    public function leaderboard () {

        $ballots = DB::table('ballots')
                    ->join('employees','ballots.user_id','=','employees.employee_id')
                    ->join('users','ballots.user_id','=','users.id')
                    ->select('employees.firstname','employees.lastname','users.image',DB::raw('ballots.user_id, count(user_id) AS occurrences, round(count(user_id)/(select count(*) from ballots where category_id=3) * 100) as percent, RANK() OVER (ORDER BY occurrences desc) as rank'))
                    ->where('ballots.category_id',2)
                    ->groupBy('ballots.user_id','employees.firstname','employees.lastname','users.image')
                    ->orderBy('occurrences', 'DESC')
                    ->limit(5)
                    ->get();


        return $ballots;
    }

}
