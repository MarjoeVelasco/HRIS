@extends('admin.adminlayouts.master')
@section('title', '| Create Category')
@section('content')
@include('inc.messages')
<div class="card">    
<div class="d-flex justify-content-center" >
<div class='col-lg-12 col-lg-offset-4' >
    <br>
    <h2 class="text-center text-info"><i class='feather icon-users'></i> Create Candidate</h2>
   
    <hr>
    {{ Form::open(array('url' => 'candidates')) }}
    {!! csrf_field() !!}
    
    <div class="form-group px-5">
        {{ Form::label('user', 'User') }}
        <select class="form-control" name="user" required>
            <option disabled selected value>-- Select Voter --</option>
            @foreach($employees as $employee)
            <option value="{{$employee->id}}">{{$employee->lastname}}, {{$employee->firstname}} </option>
            @endforeach
        </select>                        
    </div>

    <div class="form-group px-5">
        {{ Form::label('form', 'Form') }}
        <select class="form-control text-uppercase" name="form" required>
            
            @foreach($forms as $form)
            <option value="{{$form->id}}">{{$form->title}}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group px-5">

        @foreach ($categories as $category)
            {{ Form::checkbox('category[]',  $category->id ) }}
            {{ Form::label($category->title, ucfirst($category->title)) }}<br>
        @endforeach
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