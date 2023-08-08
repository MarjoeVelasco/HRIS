@extends('admin.adminlayouts.master')
@section('title', '| Create Survey')
@section('content')
@include('inc.messages')
<div class="card">    
<div class="d-flex justify-content-center" >
<div class='col-lg-12 col-lg-offset-4' >
    <br>
    <h2 class="text-center text-info"><i class='fa fa-clipboard'></i> Create Form</h2>
   
    <hr>
    {{ Form::open(array('url' => 'forms')) }}
    {!! csrf_field() !!}
    <div class="form-group px-5">
        {{ Form::label('title', 'Title') }}
        {{ Form::text('title', '', array('class' => 'form-control','required' => 'required')) }}
    </div>

    <div class="form-group px-5">
        {{ Form::label('description', 'Description') }}
        {{ Form::textarea('description', '', array('rows' => 3, 'class' => 'form-control','required' => 'required')) }}
    </div>

    <div class="form-group px-5">
        {{ Form::label('internal_notes', 'Internal Note') }}
        {{ Form::textarea('internal_notes', '', array('placeholder'=>'Optional','rows' => 5, 'class' => 'form-control')) }}
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