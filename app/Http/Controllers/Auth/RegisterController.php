<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\Employee;
use DB;
use App\ErrorLog;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request; 

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        return Validator::make($data, ['name' => ['required', 'string', 'max:120'], 'email' => ['required', 'string', 'email', 'max:255', 'unique:users'], 'password' => ['required', 'string', 'min:8', 'confirmed'], 'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:5120'], 'employee_number' => ['required', 'unique:employees'], 'item_number' => ['required', 'unique:employees'], 'lastname' => ['required'], 'firstname' => ['required'], 'position' => ['required'], 'sg' => ['required'], 'stepinc' => ['required'], 'unit' => ['required'], 'status' => ['required'], ]);

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        DB::beginTransaction();
        try
        {
            $record = User::create(['name' => $data['name'],
             'email' => $data['email'], 
             'password' => $data['password'], 
             'image' => $data['image'], ]);

            $lastInsertedId = $record->id;

            $employee = Employee::create(['employee_id' => $lastInsertedId, 
            'employee_number' => $data['employee_number'], 
            'item_number' => $data['item_number'], 
            'lastname' => $data['lastname'], 
            'firstname' => $data['firstname'], 
            'middlename' => $data['middlename'],
             'extname' => $data['extname'], 
             'position' => $data['position'], 
             'sg' => $data['sg'], 
             'stepinc' => $data['stepinc'], 
             'division' => $data['division'],
              'unit' => $data['unit'], 
              'status' => $data['status'],
              'shift'=>'regular' ]);

            if (isset($data['image']))
            {
                $image = $data['image'];
                $newImage = $record['id'] . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images') , $newImage);
                $record->image = 'images/' . $newImage;
                $record->save();
            }
            DB::commit();
        }

        catch(\Exception $e)
        {
            //rollback all transactions made to database
            DB::rollback();
            $TransactionStatus = false;
            //save error to log
            $log = new ErrorLog;
            $log->message = $e->getMessage();
            $log->file = $e->getFile();
            $log->line = $e->getLine();;
            $log->url = $_SERVER['REQUEST_URI'];
            $log->save();
            //redirect back to home with error
            
        }

        return $record;
    }
}

