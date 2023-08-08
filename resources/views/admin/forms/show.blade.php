@extends('admin.adminlayouts.master')
@section('title', '| Create Survey')
@section('content')
@include('inc.messages')
<div class="card">    
<div class="d-flex justify-content-center" >
<div class='col-lg-12 col-lg-offset-4' >
<br>
@foreach($forms as $form)
    <h4 class="text-center text-info"><b>{{$form->title}}</b> @if($form->status==0)   
            <span class="badge bg-secondary text-white">INACTIVE</span>
        @else
            <span class="badge bg-success text-white">ACTIVE</span>
        @endif     </h4><br>
    <div class="form-group px-5">
        <label>Description :</label>
        <textarea class="form-control" rows="4" readonly>{{$form->description}}</textarea>
    </div>

    <div class="form-group px-5">
        <label>Internal Note :</label>
        <textarea class="form-control" rows="4" readonly>{{$form->internal_note}}</textarea>
    </div>

    <div class="form-group px-5">
        <div class="form-row">
            <div class="col-md-6">
                <span>Start Date :</span> <span class="text-primary"><b>{{date('F d, Y h:i a', strtotime($form->start_date))}}</b></span>
            </div>
            <div class="col-md-6">
                <span>End Date :<span> <span class="text-danger"><b>{{date('F d, Y h:i a', strtotime($form->end_date))}}</b></span>
            </div>
        </div>
    </div>

    <br>
   
    <div class="form-group px-5">
        <center>
            @if($form->status==0)
                <a href="{{ route('forms.index') }}" class="btn btn-outline-success pull-right"><i class="feather icon-arrow-left"></i> Go Back</a>
                <a href="{{ route('forms.edit', $form->id) }}" class="btn btn-outline-warning pull-right"><i class="feather icon-edit"></i> Edit Form</a>
            @else
                <a href="{{ route('forms.index') }}" class="btn btn-outline-success pull-right"><i class="feather icon-arrow-left"></i> Go Back</a>
            @endif
        </center>
    </div>


@endforeach
</div>
</div>
</div>
<br>
</div>
</div>
@endsection