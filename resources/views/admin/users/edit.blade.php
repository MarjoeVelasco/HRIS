{{-- \resources\views\users\edit.blade.php --}}

@extends('admin.adminlayouts.master')

@section('title', '| Edit User')

@section('content')

<div class="d-flex justify-content-center">
<div class='col-lg-10 col-lg-offset-4'>

@include('inc.messages')

    <h2 class="text-center text-info"><i class="feather icon-edit"></i> Edit {{$user->name}}</h2>
    <hr>

    {{ Form::model($user, array('route' => array('users.update', $user->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data')) }}{{-- Form model binding to automatically populate our fields with user data --}}
    {!! csrf_field() !!}

    <div class="row">

<div class="col-md-6">

    <div class="form-group">
        {{ Form::label('image', 'Profile Picture') }}
        {{ Form::file('image', array('class' => 'form-control')) }}
    </div>

    <div class="form-row">
    <div class="form-group col-md-6">
        {{ Form::label('name', 'Display Name') }}
        {{ Form::text('name', null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group col-md-6">
        {{ Form::label('email', 'Email') }}
        {{ Form::email('email', null, array('class' => 'form-control')) }}
    </div>
    </div>








    <h5><b>Give Role</b></h5>

    <div class='form-group'>
        @foreach ($roles as $role)
            {{ Form::checkbox('roles[]',  $role->id, $user->roles ) }}
            {{ Form::label($role->name, ucfirst($role->name)) }}<br>

        @endforeach
    </div>
    </div>



    <div class="col-md-6">


    @foreach($employees as $employee)

    <div class="form-group">
        {{ Form::label('prefix', 'Prefix') }}<br>
        {{ Form::text('prefix', $employee->prefix, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('lastname', 'Lastname') }}<br>
        {{ Form::text('lastname', $employee->lastname, array('class' => 'form-control')) }}

    </div>

    <div class="form-group">
        {{ Form::label('firstname', 'Firstname') }}<br>
        {{ Form::text('firstname', $employee->firstname, array('class' => 'form-control')) }}

    </div>

    <div class="form-group">
        {{ Form::label('middlename', 'Middle Name') }}<br>
        {{ Form::text('middlename',  $employee->middlename,array('class' => 'form-control')) }}

    </div>

    <div class="form-group">
        {{ Form::label('extname', 'Extension Name ( Jr, Sr, I, II, III )') }}<br>
        {{ Form::text('extname',  $employee->extname,array('class' => 'form-control')) }}

    </div>




 <div class="form-row">
    <div class="form-group col-md-3">
        {{ Form::label('employee_number', 'Employee No.') }}<br>
        {{ Form::text('employee_number', $employee->employee_number, array('class' => 'form-control')) }}

    </div>

    

    <div class="form-group col-md-9">
        {{ Form::label('position', 'Position') }}<br>
        {{ Form::text('position',  $employee->position,array('class' => 'form-control')) }}

        </div>
    </div>


    <div class="form-row">
    <div class="form-group col-md-6">
        {{ Form::label('item_number', 'Item Number') }}<br>
        {{ Form::text('item_number', $employee->item_number, array('class' => 'form-control')) }}

    </div>

    
    <div class="form-group col-md-3">
        {{ Form::label('sg', 'SG') }}<br>

        {{ Form::select('sg', array($employee->sg=> 'SG','1' => 'SG 1', '2' => 'SG 2', '3' => 'SG 3', '4' => 'SG 4', '5' => 'SG 5', '6' => 'SG 6',
            '7' => 'SG 7', '8' => 'SG 8', '9' => 'SG 9', '10' => 'SG 10', '11' => 'SG 11', '12' => 'SG 12', '13' => 'SG 13', '14' => 'SG 14', '15' => 'SG 15',
            '16' => 'SG 16', '17' => 'SG 17', '18' => 'SG 18', '19' => 'SG 19', '20' => 'SG 20', '21' => 'SG 21', '22' => 'SG 22', '23' => 'SG 23', '24' => 'SG 24',
            '25' => 'SG 25', '26' => 'SG 26', '27' => 'SG 27', '28' => 'SG 28', '29' => 'SG 29', '30' => 'SG 30', '31' => 'SG 31', '32' => 'SG 32', '33' => 'SG 33'), '', array('class' => 'form-control')) }}
       
    </div>
    

    <div class="form-group col-md-3">
        {{ Form::label('stepinc', 'Step Inc') }}<br>

        {{ Form::select('stepinc', array($employee->stepinc=> 'SG','1' => 'Step 1', '2' => 'Step 2', '3' => 'Step 3', '4' => 'Step 4', '5' => 'Step 5', '6' => 'Step 6',
            '7' => 'Step 7', '8' => 'Step 8'), '', array('class' => 'form-control')) }}

    </div>
    </div>


    <div class="form-row">
    <div class="form-group col-md-6">
        {{ Form::label('division', 'Division') }}<br>
        {{ Form::select('division', array($employee->division=>$employee->division,'Office of the Executive Director (OED)' => 'Office of the Executive Director (OED)', 
                                          'Employment Research Division (ERD)' => 'Employment Research Division (ERD)', 
                                          'Labor and Social Relations Research Division (LSRRD)' => 'Labor and Social Relations Research Division (LSRRD)', 
                                          'Workers Welfare Research Division (WWRD)' => 'Workers Welfare Research Division (WWRD)', 
                                          'Advocacy and Pulications Division (APD)' => 'Advocacy and Pulications Division (APD)', 
                                          'Finance and Administrative Division (FAD)' => 'Finance and Administrative Division (FAD)'), null, array('class' => 'form-control')) }}


    </div>



    <div class="form-group col-md-6">
        {{ Form::label('unit', 'Unit') }}<br>
        {{ Form::text('unit',  $employee->unit,array('class' => 'form-control')) }}

        </div>
    </div>

    <div class="form-row">
    <div class="form-group col-md-3">
        {{ Form::label('status', 'Status') }}<br>
        {{ Form::text('status',  $employee->status,array('class' => 'form-control')) }}

    </div>

    <div class="form-group col-md-9">
        {{ Form::label('shift', 'Shift') }}<br>
        {{ Form::select('shift', array($employee->shift=>$employee->shift,'regular' => 'Regular (time in at 7am)', 
                         'irregular' => 'Irregular (time in anytime)'), null, array('class' => 'form-control')) }}


    </div>
    </div>

    <div class="form-group">
    {{ Form::label('birthdate', 'Birthdate') }}<br>
    {{ Form::text('birthdate', $employee->birthdate, array('class' => 'form-control date')) }}

    </div>

    <div class="form-group">
        {{ Form::label('signature', 'E-Signature') }}
        {{ Form::file('signature', array('class' => 'form-control')) }}
        <div class="bg-white">
        @if($esignature)
            @foreach($esignature as $sign)
                <center>
                    <img src="/{{$sign->signature}}" width="60%">
                </center>
            @endforeach
        @endif
        </div>

    </div>



    @endforeach
    
<div class="text-center">
    {{ Form::submit('Update', array('class' => 'btn btn-outline-primary col-md-4')) }}

    {{ Form::close() }}

    </div>
    </div>
    
    </div>
   
</div>

<script>
$('.date').datepicker({  
    format: "yyyy-mm-dd",
    viewMode: "date", 
    minViewMode: "date",
    selectYears: true,
}); 

</script>

@endsection