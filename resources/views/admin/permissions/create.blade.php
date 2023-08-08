{{-- \resources\views\permissions\create.blade.php --}}
@extends('admin.adminlayouts.master')

@section('title', '| Create Permission')

@section('content')

<div class="card">    
<div class="d-flex justify-content-center" >
<div class='col-lg-12 col-lg-offset-4' >
    <br>
    <h1 class="text-center text-info"><i class='fa fa-key'></i> Add Permission</h1>
   
    <hr>
    {{ Form::open(array('url' => 'permissions')) }}
    {!! csrf_field() !!}
    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', '', array('class' => 'form-control')) }}
    </div>
    @if(!$roles->isEmpty()) <!-- //If no roles exist yet -->
        <h4>Assign Permission to Roles</h4>

        @foreach ($roles as $role) 
            {{ Form::checkbox('roles[]',  $role->id ) }}
            {{ Form::label($role->name, ucfirst($role->name)) }}<br>

        @endforeach
    @endif
    <br>
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