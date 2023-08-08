@extends('admin.adminlayouts.master')
@section('title', '| Create Category')
@section('content')
@include('inc.messages')
<div class="card">    
<div class="d-flex justify-content-center" >
<div class='col-lg-12 col-lg-offset-4' >
    <br>
    <h2 class="text-center text-info"><i class='fa fa-bookmark'></i> Edit Category</h2>
   
    <hr>
    {{ Form::model($categories, array('route' => array('categories.update', $categories->id), 'method' => 'PUT')) }}
    {!! csrf_field() !!}

    <div class="form-group px-5">
        {{ Form::label('title', 'Category Title') }}
        {{ Form::text('title', $categories->title, array('placeholder'=>'Enter Position Title','class' => 'form-control','required' => 'required')) }}
    </div>

    <div class="form-group px-5">
        {{ Form::label('description', 'Category Description') }}
        {{ Form::textarea('description', $categories->description, array('placeholder'=>'Enter Position Details','rows' => 11, 'class' => 'form-control','required' => 'required')) }}
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