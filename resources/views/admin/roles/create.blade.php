@extends('admin.adminlayouts.master')

@section('title', '| Add Role')

@section('content')
    
<div class="card">
<div class="d-flex justify-content-center">
<div class='col-lg-12 col-lg-offset-4' >
    <br>
    <h1 class="text-center text-info"><i class='fa fa-key'></i> Add Role</h1>
    <hr>

    {{ Form::open(array('url' => 'roles')) }}
    {!! csrf_field() !!}
    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', null, array('class' => 'form-control')) }}
    </div>

    <h5><b>Assign Permissions</b></h5>

    <div class='form-group'>
        @foreach ($permissions as $permission)
            {{ Form::checkbox('permissions[]',  $permission->id ) }}
            {{ Form::label($permission->name, ucfirst($permission->name)) }}<br>

        @endforeach
    </div>
<div class="text-center">
    {{ Form::submit('Add', array('class' => 'btn btn-outline-primary col-md-4')) }}

    {{ Form::close() }}<br><br>
</div>
</div>
</div> <br>
</div>
</div>
@endsection