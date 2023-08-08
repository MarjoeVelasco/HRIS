<?php

namespace App\Http\Controllers;
use DB;
use Carbon\Carbon;
use App\ErrorLog;
use App\Forms;
use App\Exports\FormResponsesExport;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use App\User;


class FormsController extends Controller
{
    public function __construct() {
        $this->middleware(['auth', 'isElectionCommittee']); 
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $forms = Forms::where('is_archive',0)->paginate(20);
        return view('admin.forms.index')->with('forms', $forms);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin.forms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

         //BEGIN TRANSACTIONS
         DB::beginTransaction();
         try {
            Forms::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'internal_note' => $request->input('internal_notes')]);
         DB::commit();
         } catch (\Exception $e) {
            DB::rollback();

            //save error to log
            $log = new ErrorLog();
            $log->message = $e->getMessage();
            $log->file = $e->getFile();
            $log->line = $e->getLine();
            $log->url = $_SERVER['REQUEST_URI'];
            $log->save();
            //redirect back to home with error
            return back()->with(
                'error',
                'Execution Error. Record Not Saved! Please contact the administrator.'
            );
        }
      
        return back()->with('success', 'Form added');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\forms  $forms
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $forms = Forms::where('id',$id)->get();
        return view('admin.forms.show')->with('forms',$forms);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\forms  $forms
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //dd($forms);
        $form = Forms::findOrFail($id);
        return view('admin.forms.edit')->with('form',$form);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\forms  $forms
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
     
        $forms = Forms::find($id);
        $forms->title = $request->input('title');
        $forms->description = $request->input('description');
        $forms->internal_note = $request->input('internal_note');
        $forms->save();

        return redirect('/forms')->with('success','Form has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\forms  $forms
     * @return \Illuminate\Http\Response
     */
    public function destroy(forms $forms)
    {
        //
    }

    public function export($id) {

        $active = Forms::select('title')->where('id',$id)->first();
        return Excel::download(new FormResponsesExport($id), $active->title . ' election_results.csv');
    }

    public function startForm(Request $request) {
        //check if it has existing active form
        $active = Forms::where('status',1)->get();
    
        if(!$active->isEmpty()) {
            return back()->with('error', 'An existing form is active, please disable first');
        }
        else {
            $forms = Forms::find($request->input('id'));
            $forms->start_date = date('F d, Y h:i a');
            $forms->end_date = null;
            $forms->status = 1;
            $forms->save();

            //$this->voteReminder(true);
            
        }

        return back()->with('success', $forms->title.' has successfully started');
    }

    public function endForm(Request $request) {
        $forms = Forms::find($request->input('id'));
        $forms->end_date = date('F d, Y h:i a');
        $forms->status = 0;
        $forms->save();

        //$this->voteReminder(false);

        return back()->with('success', $forms->title.' has ended');
    }

    public function archiveForm(Request $request) {
        //check if form is closed
        $form = Forms::where('id',$request->input('id'))->first();
        if($form->status==1) {
            return back()->with('error', 'Selected Form '.$form->title. ' is still active, please close it before archiving');
        }
        else {
            $forms = Forms::find($request->input('id'));
            $forms->is_archive = 1;
            $forms->save();
        }
        return back()->with('success', $form->title.' has been archived');
    }

    public function voteReminder($status) {
        
        $res = User::whereHas("roles", function ($q) {
            $q->where("name", "Election Committee");
            })->select('email')
            ->get();
        
        $email_party = array();

        foreach($res as $a) {

            array_push($email_party, $a->email);
        }

        $to_name="ILS Family";
        $to_email='ilsdole@ils.dole.gov.ph';
       
        $data = array('email' => 'mvelasco@ils.dole.gov.ph', 'name' => 'Marjoe Velasco');

        if($status) {
            Mail::send('votereminder', $data, function ($message) use ($email_party,$to_name, $to_email) {
            $message->to($to_email, $to_name)->cc($email_party)->subject('ELECTIONS ARE NOW OPEN!');
            $message->from('noreply@ils.dole.gov.ph', 'TALMS');        
            });
        }

        else {
            Mail::send('voteclosed', $data, function ($message) use ($email_party,$to_name, $to_email) {
            $message->to($to_email, $to_name)->cc($email_party)->subject('ELECTIONS ARE NOW CLOSED!');
            $message->from('noreply@ils.dole.gov.ph', 'TALMS');        
            });
        }


    }

}
