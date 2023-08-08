<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\ErrorLog;
use DB;
use Carbon\Carbon;
use App\PasswordSecurity;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        return view('users.settings');
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
        $user = User::find($id);
        $this->validate($request, ['password' => 'required|min:8|confirmed']);

        //hash user password

        $old_password = $request->input('old_password');


        if (!Hash::check($old_password, $user->password)) {
            return back()->with('error', 'Incorrect old password');
        }

        if ($request->get('password') != $request->get('password_confirmation')) 
        {
            return back()->with('error', 'Password doesnt match');
        } 
        
        else {
            DB::beginTransaction();
            try {
                $user->password = $request->get('password');
                $user->save();

                //reset password expiration
                if(PasswordSecurity::where('user_id', auth()->user()->id)->exists())
                {
                      
                    PasswordSecurity::where('user_id',auth()->user()->id)
                            ->update(['password_updated_at' => Carbon::now()]);
                                  
                }


                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                $log = new ErrorLog();
                $log->message = $e->getMessage();
                $log->file = $e->getFile();
                $log->line = $e->getLine();
                $log->url = $_SERVER['REQUEST_URI'];
                $log->save();
                return back()->with(
                    'error',
                    'Execution Error. Record Not Saved! Please contact the administrator'
                );
            }
            return back()->with('success', 'Password reset success!');
        }
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
