@extends('admin.adminlayouts.master')
@section('title', '| Add Pulic IP Address')
@section('content')
@include('inc.messages')
<div class="card">    
<div class="d-flex justify-content-center" >
<div class='col-lg-12 col-lg-offset-4' >
    <br>
    <h2 class="text-center text-info"><i class='fa fa-bookmark'></i> Add IP Address</h2>
   
    <hr>
    {{ Form::open(array('url' => 'address')) }}
    
    <div class="form-group px-5">
      <p>Utilizing this IP address, TALMS can determine whether a user is connected to the ILS network, thereby establishing if the employee is working from home or on-site.</p>
    </div>
    
    {!! csrf_field() !!}
    <div class="form-group px-5">
        {{ Form::label('title', 'IP Adress') }}
        {{ Form::text('title', '', array('name' => 'ip_address' ,'placeholder'=>'Enter IP Address','class' => 'form-control','required' => 'required')) }}
    </div>

    <div class="form-group px-5">
        {{ Form::label('description', 'Description (optional)') }}
        {{ Form::textarea('description', '', array('name'=>'desc','placeholder'=>'Enter internal notes','rows' => 5, 'class' => 'form-control')) }}
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