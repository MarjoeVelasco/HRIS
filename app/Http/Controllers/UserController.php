<?php
namespace App\Http\Controllers;
//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use App\Employee;
use App\User;
use App\Leaves;
use App\Attendance;
use Carbon\Carbon;
use App\PasswordSecurity;
use Illuminate\Support\Facades\Mail;
use App\ErrorLog;
use Auth;
use DB;
//Enables us to output flash messaging
use Session;
class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isAdmin']);
        //isAdmin middleware lets only users with a specific permission to access these resources
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Get all users and pass it to the view
        //$users = User::get();
        $users = User::paginate(20);
        //$users = DB::table('users')->paginate(15);
        return view('admin.users.index')->with('users', $users);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Get all roles and pass it to the view
        $roles = Role::get();
        return view('admin.users.create', ['roles' => $roles]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //create user

        $lastInsertedId = null;
        $TransactionStatus = false;
        //Validation of fields
        $this->validate($request, [
            'name' => 'required|max:120',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'employee_number' => 'required|unique:employees',
            'item_number' => 'required|unique:employees',
            'lastname' => 'required',
            'firstname' => 'required',
            'position' => 'required',
            'birthdate' => 'required',
            'sg' => 'required',
            'stepinc' => 'required',
            'unit' => 'required',
            'status' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);
        //BEGIN TRANSACTIONS
        DB::beginTransaction();
        try {
            //store email, name, password and image to user table
            $user = User::create($request->only('email', 'name', 'password'));
            //get last inserted id
            $lastInsertedId = $user->id;
            //store to employee table
            $employee = Employee::create([
                'employee_id' => $lastInsertedId,
                'employee_number' => $request->input('employee_number'),
                'item_number' => $request->input('item_number'),
                'prefix' => $request->input('prefix'),
                'lastname' => $request->input('lastname'),
                'firstname' => $request->input('firstname'),
                'middlename' => $request->input('middlename'),
                'extname' => $request->input('extname'),
                'position' => $request->input('position'),
                'sg' => $request->get('sg'),
                'stepinc' => $request->input('stepinc'),
                'division' => $request->get('division'),
                'unit' => $request->input('unit'),
                'status' => $request->input('status'),
                'shift' => $request->get('shift'),
                'birthdate' => $request->input('birthdate'),
            ]);

            //set password expiration of new user
            $passwordSecurity = PasswordSecurity::create([
                'user_id' => $lastInsertedId,
                'password_expiry_days' => 30,
                'password_updated_at' => Carbon::now(),
            ]);

            //if transactions are successful, commit changes to database
            DB::commit();
            $TransactionStatus = true;
        } catch (\Exception $e) {
            //rollback all transactions made to database
            DB::rollback();
            $TransactionStatus = false;
            //save error to log
            $log = new ErrorLog();
            $log->message = $e->getMessage();
            $log->file = $e->getFile();
            $log->line = $e->getLine();
            $log->url = $_SERVER['REQUEST_URI'];
            $log->save();
            //redirect back to home with error
            return redirect('/users/create')->with(
                'error',
                'Execution Error. Record Not Saved! Please contact the administrator.'
            );
        }
        if ($TransactionStatus) {
            //if user has image uploaded
            if (null !== $request->file('image')) {
                //BEGIN TRANSACTIONS
                DB::beginTransaction();
                try {
                    //rename image and store to public folder
                    $image = $request->file('image');
                    $newImage = $user['id'].'.'.$image->getClientOriginalExtension();
                    $image->move(public_path('images'), $newImage);
                    $user->image = 'images/' . $newImage;
                    $saved = $user->save();
                    if (!$saved) {
                        return redirect('/users/create')->with(
                            'error',
                            'Execution Error. Record Not Saved!'
                        );
                    }
                    //set image to newly added user
                    DB::update('update users set image = ? where id = ?', [
                        'images/' . $newImage,
                        $lastInsertedId,
                    ]);
                    //if transactions are successful, commit changes to database
                    DB::commit();
                } catch (\Exception $e) {
                    //rollback all transactions made to database
                    DB::rollback();
                    $log = new ErrorLog();
                    $log->message = $e->getMessage();
                    $log->file = $e->getFile();
                    $log->line = $e->getLine();
                    $log->url = $_SERVER['REQUEST_URI'];
                    $log->save();
                    //redirect back to home with error
                    return redirect('/users/create')->with(
                        'error',
                        'Execution Error. Record Not Saved! Please contact the administrator'
                    );
                }
            }
        }
        $roles = $request['roles']; //Retrieving the roles field
        //Checking if a role was selected
        if (isset($roles)) {
            foreach ($roles as $role) {
                $role_r = Role::where('id', '=', $role)->firstOrFail();
                $user->assignRole($role_r); //Assigning role to user
            }
        }
        // if there are no errors
        return redirect('/users/create')->with(
            'success',
            'Employee successfully added.'
        );
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('users');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $esignature = DB::table('employees')
        ->where('employee_id', $id)
        ->select('signature')
        ->get();

        $user = User::findOrFail($id); //Get user with specified id
        //$employees = Employee::findOrFail($id); //Get employee with specified id
        $employees = DB::table('employees')
            ->where('employee_id', $id)
            ->get();
        $roles = Role::get(); //Get all roles
        return view('admin.users.edit', compact('user', 'employees', 'roles','esignature')); //pass user and roles data to view
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
        $TransactionStatus = false;
        //Validation of fields
        $this->validate($request, [
            'name' => 'required|max:120',
            'email' => 'required|email|unique:users,email,' . $id,
            'employee_number' =>
                'required|unique:employees,employee_number,' .
                $id .
                ',employee_id',
            'item_number' =>
                'required|unique:employees,item_number,' . $id . ',employee_id',
            'lastname' => 'required',
            'firstname' => 'required',
            'position' => 'required',
            'sg' => 'required',
            'stepinc' => 'required',
            'unit' => 'required',
            'status' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'signature' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);
        //Begin transaction
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id); //Get role specified by id
            $employees = DB::table('employees')
                ->where('employee_id', $id)
                ->get();
            $employee_number = $request->input('employee_number');
            $item_number = $request->input('item_number');
            $prefix = $request->input('prefix');
            $lastname = $request->input('lastname');
            $firstname = strip_tags($request->input('firstname'));
            $middlename = $request->input('middlename');
            $extname = $request->input('extname');
            $position = $request->input('position');
            $sg = $request->get('sg');
            $stepinc = $request->input('stepinc');
            $division = $request->get('division');
            $unit = $request->input('unit');
            $status = $request->input('status');
            $shift = $request->get('shift');
            $birthdate = $request->input('birthdate');
            DB::update(
                'update employees set employee_number = ?, item_number = ?, prefix = ?, lastname = ?, firstname = ?, middlename = ?, extname = ?, position = ?, sg = ?, stepinc = ?, division = ?, unit = ?, status = ?, shift = ?, birthdate = ? where employee_id = ?',
                [
                    $employee_number,
                    $item_number,
                    $prefix,
                    $lastname,
                    $firstname,
                    $middlename,
                    $extname,
                    $position,
                    $sg,
                    $stepinc,
                    $division,
                    $unit,
                    $status,
                    $shift,
                    $birthdate,
                    $id,
                ]
            );
            $input = $request->only(['name', 'email']); //Retreive the name, email and password fields
            $roles = $request['roles']; //Retreive all roles
            $user->fill($input)->save();
            DB::commit();
            //if all good, commit transactions to database
            $TransactionStatus = true;
        } catch (\Exception $e) {
            //rollback all transactions made to database
            DB::rollback();
            $log = new ErrorLog();
            $log->message = $e->getMessage();
            $log->file = $e->getFile();
            $log->line = $e->getLine();
            $log->url = $_SERVER['REQUEST_URI'];
            $log->save();
            //redirect back to home with error
            return redirect('/users/create')->with(
                'error',
                'Execution Error. Record Not Saved!'
            );
        }
        if ($TransactionStatus) {
            //if user has image uploaded
            if (null !== $request->file('image')) {
                //BEGIN TRANSACTIONS
                DB::beginTransaction();
                try {
                    //rename image and store to public folder
                    $image = $request->file('image');
                    $newImage =
                        $user['id'] .
                        '.' .
                        $image->getClientOriginalExtension();
                    $image->move(public_path('images'), $newImage);
                    $user->image = 'images/' . $newImage;
                    $saved = $user->save();
                    if (!$saved) {
                        return redirect('/users/create')->with(
                            'error',
                            'Execution Error. Record Not Saved!'
                        );
                    }
                    //set image to newly added user
                    DB::update('update users set image = ? where id = ?', [
                        'images/' . $newImage,
                        $id,
                    ]);
                    //if transactions are successful, commit changes to database
                    DB::commit();
                } catch (\Exception $e) {
                    //rollback all transactions made to database
                    DB::rollback();
                    $log = new ErrorLog();
                    $log->message = $e->getMessage();
                    $log->file = $e->getFile();
                    $log->line = $e->getLine();
                    $log->url = $_SERVER['REQUEST_URI'];
                    $log->save();
                    //redirect back to home with error
                    return redirect('/users/create')->with(
                        'error',
                        'Execution Error. Record Not Saved!'
                    );
                }
            }

            //if user has signature uploaded
            if (null !== $request->file('signature')) {
                //BEGIN TRANSACTIONS
                DB::beginTransaction();
                try {
                    //rename image and store to public folder
                    $image = $request->file('signature');
                    $newImage_signature ='signature'.$user['id'] .'.' .$image->getClientOriginalExtension();
                    $image->move(public_path('images'), $newImage_signature);

                    $employee_signature = Employee::where('employee_id',$id)
                    ->select('id')
                    ->first();

                    $employee_signature2 = Employee::find($employee_signature->id);
                    $employee_signature2->signature = 'images/' . $newImage_signature;
                    $saved2 = $employee_signature2->save();


                    if (!$saved2) {
                        return redirect('/users/create')->with(
                            'error',
                            'Execution Error. Record Not Saved!'
                        );
                    }

                    //set image to newly added user
                    DB::update('update employees set signature = ? where employee_id = ?', [
                        'images/' . $newImage_signature,
                        $id,
                    ]);
                    //if transactions are successful, commit changes to database
                    DB::commit();
                } catch (\Exception $e) {
                    //rollback all transactions made to database
                    DB::rollback();
                    $log = new ErrorLog();
                    $log->message = $e->getMessage();
                    $log->file = $e->getFile();
                    $log->line = $e->getLine();
                    $log->url = $_SERVER['REQUEST_URI'];
                    $log->save();
                    //redirect back to home with error
                    return redirect('/users/create')->with(
                        'error',
                        'Execution Error. Record Not Saved!'
                    );
                }
            }
        }

        
        if (isset($roles)) {
            $user->roles()->sync($roles); //If one or more role is selected associate user to roles
        } else {
            $user->roles()->detach(); //If no role is selected remove exisiting role associated to a user
        }
        return redirect('/users')->with(
            'success',
            'Employee successfully Edited.'
        );
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function destroy(Request $request) {
    //Find a user with a given id and delete
        $id=$request->input('delete_user_id');
    
    
        $user = User::findOrFail($id); 
        $user->delete();
    
        DB::delete('delete from employees where employee_id = ?',[$id]);
        
        return redirect('/users')->with('success','Employee successfully Deleted.');
    }*/
    public function view($id)
    {
        $users = DB::table('users')
            ->join('employees', 'users.id', '=', 'employees.employee_id')
            ->where('users.id', '=', $id)
            ->get();
        return response()->json(['data' => $users]);
    }
    public function disable(Request $request)
    {
        //Find a user with a given id and delete
        $id = $request->input('delete_user_id');
        DB::beginTransaction();
        try {

            $user = User::find($id);
            $user->is_disabled = 1;
            $user->save();

            DB::update(
                'update employees set status = ? where employee_id = ?',
                ['Inactive', $id,]
            );



/*
            //delete from user table
            $user = User::findOrFail($id);
            $user->delete();
            //delete from employee table
            $employee = Employee::where('employee_id', $id);
            $employee->delete();
            //delete from attendance employee
            $attendance = Attendance::where('employee_id', $id);
            $attendance->delete();
            //delete from leaves
            $leaves = Leaves::where('employee_id', $id);
            $leaves->delete();
*/



            DB::commit();
        } catch (\Exception $e) {
            //rollback all transactions made to database
            DB::rollback();
            $log = new ErrorLog();
            $log->message = $e->getMessage();
            $log->file = $e->getFile();
            $log->line = $e->getLine();
            $log->url = $_SERVER['REQUEST_URI'];
            $log->save();
            //redirect back to home with error
            return redirect('/users/create')->with(
                'error',
                'Execution Error. Record Not Saved!'
            );
        }
        return redirect('/users')->with(
            'success',
            'Employee disabled.'
        );
    }


    public function enable(Request $request)
    {
        //Find a user with a given id and delete
        $id = $request->input('delete_user_id');
        DB::beginTransaction();
        try {

            $user = User::find($id);
            $user->is_disabled = 0;
            $user->save();

            DB::update(
                'update employees set status = ? where employee_id = ?',
                ['Active', $id,]
            );



/*
            //delete from user table
            $user = User::findOrFail($id);
            $user->delete();
            //delete from employee table
            $employee = Employee::where('employee_id', $id);
            $employee->delete();
            //delete from attendance employee
            $attendance = Attendance::where('employee_id', $id);
            $attendance->delete();
            //delete from leaves
            $leaves = Leaves::where('employee_id', $id);
            $leaves->delete();
*/



            DB::commit();
        } catch (\Exception $e) {
            //rollback all transactions made to database
            DB::rollback();
            $log = new ErrorLog();
            $log->message = $e->getMessage();
            $log->file = $e->getFile();
            $log->line = $e->getLine();
            $log->url = $_SERVER['REQUEST_URI'];
            $log->save();
            //redirect back to home with error
            return redirect('/users/create')->with(
                'error',
                'Execution Error. Record Not Saved!'
            );
        }
        return redirect('/users')->with(
            'success',
            'Employee enabled.'
        );
    }

    public function resetpassword(Request $request)
    {
        //logic for password reset
        //first get id
        //query ID to get employee details
        //upon reset, send email of new password
        $email_details = User::join(
            'employees',
            'employees.employee_id',
            'users.id'
        )
            ->select('employees.firstname', 'employees.lastname', 'users.email')
            ->where('users.id', $request->input('id'))
            ->first();
        $name = $email_details->firstname;
        //get id
        //query to get email
        //query to get name
        //notice of change of email
        $this->validate($request, ['password' => 'required|min:8|confirmed']);
        $id = $request->input('id');
        $currentYear = date('Y');
        DB::beginTransaction();
        try {
            $user = User::where('id', $id)->first();
            $input = $request->only(['id', 'password']);
            $user->fill($input)->save();

            //reset password expiration
            if(PasswordSecurity::where('user_id', $id)->exists())
            {
                PasswordSecurity::where('user_id',$id)
                    ->update(['password_updated_at' => Carbon::now()]);                  
            }

            //if password expiration doesnt exist yet
            else
            {
            $passwordSecurity = PasswordSecurity::create([
                'user_id' => $id,
                'password_expiry_days' => 90,
                'password_updated_at' => Carbon::now(),
            ]);

        }





            //script for sending emails
			/*
            $to_name = $name;
            $to_email = $email_details->email;
            $data = [
                'email' => $to_email,
                'name' => $to_name,
                'year' => $currentYear,
                "new_password" => $request->input('password'),
            ];
            Mail::send('emails', $data, function ($message) use (
                $to_name,
                $to_email
            ) {
                $message
                    ->to($to_email, $to_name)
                    ->subject('Notice of password reset');
                $message->from('noreply@ils.dole.gov.ph', 'ISLAAM');
            });
			*/
            //end
            DB::commit();
        } catch (\Exception $e) {
            //rollback all transactions made to database
            DB::rollback();
            $log = new ErrorLog();
            $log->message = $e->getMessage();
            $log->file = $e->getFile();
            $log->line = $e->getLine();
            $log->url = $_SERVER['REQUEST_URI'];
            $log->save();
            //redirect back to home with error
            return redirect('/users')->with(
                'error',
                'Execution Error. Record Not Saved! Please contact the administrator'
            );
        }
        return redirect('/users')->with(
            'success',
            'Password has been reset successfully'
        );
    }
}
