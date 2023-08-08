{{-- \resources\views\users\create.blade.php --}}
@extends('admin.adminlayouts.master')

@section('title', '| Add User')

@section('content')

<div class="d-flex justify-content-center">
<div class='col-lg-10 col-lg-offset-4' style="background-color:white;">
<br>
@include('inc.messages')
    <br>
    <h2 class="text-center text-info"><i class='fa fa-user-plus'></i> Add User</h2>
    <hr>

    {{ Form::open(array('url' => 'users','enctype' => 'multipart/form-data')) }}
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
        {{ Form::text('name', '', array('class' => 'form-control')) }}
    </div>

    <div class="form-group col-md-6">
        {{ Form::label('email', 'Email') }}
        {{ Form::email('email', '', array('class' => 'form-control')) }}
    </div>
    </div>

   

    <div class="form-row">
    <div class="form-group col-md-6">
        {{ Form::label('password', 'Password') }}<br>
        {{ Form::password('password', array('class' => 'form-control')) }}

    </div>

    <div class="form-group col-md-6">
        {{ Form::label('password', 'Confirm Password') }}<br>
        {{ Form::password('password_confirmation', array('class' => 'form-control')) }}

    </div>
    </div>
    <h5><b>Give Role</b></h5>
    
    <div class='form-group'>
        @foreach ($roles as $role)
            {{ Form::checkbox('roles[]',  $role->id ) }}
            {{ Form::label($role->name, ucfirst($role->name)) }}<br>

        @endforeach
    </div>
    </div>
    

    <div class="col-md-6">
   
    <div class="form-group">
        {{ Form::label('prefix', 'Prefix') }}<br>
        {{ Form::text('prefix', '', array('class' => 'form-control')) }}

    </div>


    <div class="form-group">
        {{ Form::label('lastname', 'Lastname') }}<br>
        {{ Form::text('lastname', '', array('class' => 'form-control')) }}

    </div>

    <div class="form-group">
        {{ Form::label('firstname', 'Firstname') }}<br>
        {{ Form::text('firstname', '', array('class' => 'form-control')) }}

    </div>

    <div class="form-group">
        {{ Form::label('middlename', 'Middle Name') }}<br>
        {{ Form::text('middlename',  '',array('class' => 'form-control')) }}

    </div>

    <div class="form-group">
        {{ Form::label('extname', 'Extension Name ( Jr, Sr, I, II, III )') }}<br>
        {{ Form::text('extname',  '',array('class' => 'form-control')) }}

    </div>

    <div class="form-row">
    <div class="form-group col-md-3">
        {{ Form::label('employee_number', 'Employee No.') }}<br>
        {{ Form::text('employee_number', '', array('class' => 'form-control')) }}

    </div>

    <div class="form-group col-md-9">
        {{ Form::label('position', 'Position') }}<br>
        {{ Form::text('position',  '',array('class' => 'form-control')) }}

    </div>
    </div>

    <div class="form-row">
    <div class="form-group col-md-6">
        {{ Form::label('item_number', 'Item Number') }}<br>
        {{ Form::text('item_number', '', array('class' => 'form-control')) }}

    </div>

    <div class="form-group col-md-3">
        {{ Form::label('sg', 'SG') }}<br>

        {{ Form::select('sg', array('1' => 'SG 1', '2' => 'SG 2', '3' => 'SG 3', '4' => 'SG 4', '5' => 'SG 5', '6' => 'SG 6',
            '7' => 'SG 7', '8' => 'SG 8', '9' => 'SG 9', '10' => 'SG 10', '11' => 'SG 11', '12' => 'SG 12', '13' => 'SG 13', '14' => 'SG 14', '15' => 'SG 15',
            '16' => 'SG 16', '17' => 'SG 17', '18' => 'SG 18', '19' => 'SG 19', '20' => 'SG 20', '21' => 'SG 21', '22' => 'SG 22', '23' => 'SG 23', '24' => 'SG 24',
            '25' => 'SG 25', '26' => 'SG 26', '27' => 'SG 27', '28' => 'SG 28', '29' => 'SG 29', '30' => 'SG 30', '31' => 'SG 31', '32' => 'SG 32', '33' => 'SG 33'), "null", array('class' => 'form-control')) }}
       
    </div>

    <div class="form-group col-md-3">
        {{ Form::label('stepinc', 'Step Inc') }}<br>

        {{ Form::select('stepinc', array('1' => 'Step 1', '2' => 'Step 2', '3' => 'Step 3', '4' => 'Step 4', '5' => 'Step 5', '6' => 'Step 6',
            '7' => 'Step 7', '8' => 'Step 8'), '', array('class' => 'form-control')) }}

    </div>
    </div>

    <div class="form-row">
    <div class="form-group col-md-6">
        {{ Form::label('division', 'Division') }}<br>
        {{ Form::select('division', array('Office of the Executive Director (OED)' => 'Office of the Executive Director (OED)', 
                                          'Employment Research Division (ERD)' => 'Employment Research Division (ERD)', 
                                          'Labor and Social Relations Research Division (LSRRD)' => 'Labor and Social Relations Research Division (LSRRD)', 
                                          'Workers Welfare Research Division (WWRD)' => 'Workers Welfare Research Division (WWRD)', 
                                          'Advocacy and Pulications Division (APD)' => 'Advocacy and Pulications Division (APD)', 
                                          'Finance and Administrative Division (FAD)' => 'Finance and Administrative Division (FAD)'), null, array('class' => 'form-control')) }}


    </div>

    <div class="form-group col-md-6">
        {{ Form::label('unit', 'Unit') }}<br>
        {{ Form::text('unit',  '',array('class' => 'form-control')) }}

    </div>
    </div>
    
    <div class="form-row">
    <div class="form-group col-md-3">
        {{ Form::label('status', 'Status') }}<br>
        {{ Form::select('status', array('Active' => 'Active'), null ,array('class' => 'form-control')) }}
    </div>

    <div class="form-group col-md-9">
        {{ Form::label('shift', 'Shift') }}<br>
        {{ Form::select('shift', array('regular' => 'Regular (time in at 7am)', 
                         'irregular' => 'Irregular (time in anytime)'), null ,array('class' => 'form-control')) }}
    </div>
    </div>

    <div class="form-group">
    {{ Form::label('birthdate', 'Birthdate') }}<br>
    {{ Form::text('birthdate', '', array('class' => 'form-control date')) }}
    </div>

    
    <div class="text-center">
    {{ Form::submit('Add', array('class' => 'btn btn-outline-primary col-md-4 text-center')) }}

    {{ Form::close() }}
</div>
    </div>
    
    </div>
   

<br>

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