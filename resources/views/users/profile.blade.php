@extends('users.userslayouts.master')
@section('title', 'Settings')
@section('content')
<div class="container d-flex justify-content-center  mt-2">
   <div class="card col-md-6" style="padding:0;">


   <h3 class="bg-info card-header text-center" style="color:white">
    <i class="feather icon-user"></i> Update Profile
        </h3>


            <div class="card-body text-center">
            @include('inc.messages')
            <img src="{{ Auth::user()->image }}" alt="{{ Auth::user()->name  }}'s image" width="150px" height="150px" class="rounded-circle rounded mx-auto d-block"><br>



{{ Form::model($user, array('route' => array('profile.update', $user->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data')) }}{{-- Form model binding to automatically populate our fields with user data --}}
{!! csrf_field() !!}

<div class="row">

<div class="col-md-12">

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
<br>

<div class="col-md-12">


@foreach($employees as $employee)

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
    {{ Form::text('status',  $employee->status,array('class' => 'form-control','readonly' => 'true')) }}

</div>

<div class="form-group col-md-9">
{{ Form::label('shift', 'Shift') }}<br>
    {{ Form::text('shift',  $employee->shift,array('class' => 'form-control','readonly' => 'true')) }}
</div>
</div>

<div class="form-group">
    {{ Form::label('birthdate', 'Birthdate') }}<br>
    {{ Form::text('birthdate', $employee->birthdate, array('class' => 'form-control date')) }}

</div>

<div class="form-group">
    <p>E-signature</p>
    @if($employee->signature)
        <img src="/{{$employee->signature}}" width="50%" >
    @endif
</div>



@endforeach

<div class="text-center">
{{ Form::submit('Update', array('class' => 'btn btn-outline-primary col-md-4')) }}

{{ Form::close() }}



</div>
        </div>

</div>

</div></div></div></div>

<script>
$('.date').datepicker({  
    format: "yyyy-mm-dd",
    viewMode: "date", 
    minViewMode: "date",
    selectYears: true,
}); 

</script>

@stop