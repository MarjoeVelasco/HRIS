<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use DB;
use App\Employee;
use App\User;
use App\ErrorLog;
//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Session;
class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       

        $user = User::findOrFail(auth()->user()->id); //Get user with specified id
        //$employees = Employee::findOrFail($id); //Get employee with specified id
        $employees = DB::table('employees')
            ->where('employee_id', auth()->user()->id)
            ->get();
       
      

        return view('users.profile', compact('user', 'employees'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate name and permissions field
        
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
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
            $birthdate = $request->get('birthdate');
            DB::update(
                'update employees set employee_number = ?, item_number = ?, lastname = ?, firstname = ?, middlename = ?, extname = ?, position = ?, sg = ?, stepinc = ?, division = ?, unit = ?, status = ?, shift = ? , birthdate = ? where employee_id = ?',
                [
                    $employee_number,
                    $item_number,
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
                    return redirect('/profile')->with(
                        'error',
                        'Execution Error. Record Not Saved!'
                    );
                }
            }
        }
      
        return redirect('/profile')->with(
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
    public function destroy($id)
    {
        
    }
}
