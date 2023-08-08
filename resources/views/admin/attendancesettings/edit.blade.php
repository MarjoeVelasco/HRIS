@extends('admin.adminlayouts.master')
@section('title', '| Change Settings')
@section('content')
@include('inc.messages')
<div class="card">    
<div class="d-flex justify-content-center" >
<div class='col-lg-12 col-lg-offset-4' >
    <br>
    <h2 class="text-center text-info"><i class='fa fa-bookmark'></i> Edit {{$settings->system_setting_name}}</h2>
   
    <hr>
    {{ Form::model($settings, array('route' => array('attendance-setting.update', $settings->id), 'method' => 'PUT')) }}
    {!! csrf_field() !!}

    <div class="form-group px-5">
        {{ Form::label('title', $settings->system_setting_name) }}
        {{ Form::select('system_value', array($settings->system_setting_value => $settings->system_setting_value,'hybrid' => 'hybrid', 'onsite' => 'onsite'), '', array('class' => 'form-control')) }}
        <br>
        <span>Hybrid - will allow users to time in/time out anywhere</span><br>
        <span>Onsite - will restrict users to time in/time out only in office</span>
      </div>

  
    <div class="text-center">
    {{ Form::submit('Update', array('class' => 'btn btn-outline-primary col-md-4')) }}

    {{ Form::close() }}



    <br><br>
    
</div>
</div>
</div>
<br>
</div>
</div>
@endsection