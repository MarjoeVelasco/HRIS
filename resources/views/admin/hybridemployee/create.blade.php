@extends('admin.adminlayouts.master')
@section('title', '| Create Category')
@section('content')
@include('inc.messages')
<div class="card">    
<div class="d-flex justify-content-center" >
<div class='col-lg-12 col-lg-offset-4' >
    <br>
    <h2 class="text-center text-info"><i class='feather icon-users'></i> Add</h2>
   
    <hr>
    {{ Form::open(array('url' => 'hybrid-employee')) }}
    {!! csrf_field() !!}
    
    <div class="form-group px-5">
        {{ Form::label('user', 'User') }}

        <select class="form-control" name="user" required>
            <option disabled selected value>-- Select Employee --</option>
            @foreach($users as $user)
            <option value="{{$user->employee_id}}">{{$user->lastname}}, {{$user->firstname}} </option>
            @endforeach
        </select>                        
    </div>
    

    <div class="text-center">
    {{ Form::submit('Add', array('class' => 'btn btn-outline-primary col-md-4')) }}

    {{ Form::close() }}
    <br><br>
    
</div>
</div>
</div>
<br>
</div>
</div>
@endsection