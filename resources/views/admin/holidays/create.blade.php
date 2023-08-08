@extends('admin.adminlayouts.master')
@section('title', '| Create Holiday')
@section('content')
@include('inc.messages')
<div class="card">    
    <div class="d-flex justify-content-center">
        <div class='col-lg-12 col-lg-offset-4'>
            <br>
            <h2 class="text-center text-info"><i class='fa fa-clipboard'></i> Create Form</h2>
            <hr>
            {{ Form::open(array('url' => 'manageholidays')) }}
            {!! csrf_field() !!}
                <div class="form-group px-5">
                    <label for="title">Holiday Name</label>
                    <input class="form-control" type="text" name="holiday_name" required>
                </div>

                <div class="form-group px-5">
                  <label for="time_in_date">Inclusive Dates (YYYY-MM-DD)</label>
				          <input class="date form-control" type="text" name="inclusive_dates" placeholder="YYYY-MM-DD" required>
                </div>

                <div class="form-group px-5">
                  <label for="time_in_time">Remarks</label>
                  <textarea class="form-control" name="remarks" required></textarea>
                </div>

                <div class="row">
                  <div class="column">
                    <div class="form-group px-5">
                      <label>Affected/Involved Employees</label><br>
                      <span><b>ERD</b></span><br>
                        @foreach($users as $user)
                          @if($user->division == "Employment Research Division (ERD)")
                            <input type="checkbox" name="users[]" value="{{$user->id}}"> {{$user->firstname}} {{$user->lastname}}<br>
                          @endif
                        @endforeach
                    </div>
                  </div>

                  <div class="column">
                    <div class="form-group px-5">
                      <label></label><br>
                      <span><b>WWRD</b></span><br>
                        @foreach($users as $user)
                          @if($user->division == "Workers Welfare Research Division (WWRD)")
                            <input type="checkbox" name="users[]" value="{{$user->id}}"> {{$user->firstname}} {{$user->lastname}}<br>
                          @endif
                        @endforeach
                    </div>
                  </div>

                  <div class="column">
                    <div class="form-group px-5">
                      <label></label><br>
                      <span><b>LSRRD</b></span><br>
                        @foreach($users as $user)
                          @if($user->division == "Labor and Social Relations Research Division (LSRRD)")
                            <input type="checkbox" name="users[]" value="{{$user->id}}"> {{$user->firstname}} {{$user->lastname}}<br>
                          @endif
                        @endforeach
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="column">
                    <div class="form-group px-5">
                      <span><b>OED</b></span><br>
                        @foreach($users as $user)
                          @if($user->division == "Office of the Executive Director (OED)")
                            <input type="checkbox" name="users[]" value="{{$user->id}}"> {{$user->firstname}} {{$user->lastname}}<br>
                          @endif
                        @endforeach
                    </div>
                  </div>

                  <div class="column">
                    <div class="form-group px-5">
                      <span><b>FAD</b></span><br>
                        @foreach($users as $user)
                          @if($user->division == "Finance and Administrative Division (FAD)")
                            <input type="checkbox" name="users[]" value="{{$user->id}}"> {{$user->firstname}} {{$user->lastname}}<br>
                          @endif
                        @endforeach
                    </div>
                  </div>

                  <div class="column">
                    <div class="form-group px-5">
                      <span><b>APD</b></span><br>
                        @foreach($users as $user)
                          @if($user->division == "Advocacy and Pulications Division (APD)")
                            <input type="checkbox" name="users[]" value="{{$user->id}}"> {{$user->firstname}} {{$user->lastname}}<br>
                          @endif
                        @endforeach
                    </div>
                  </div>
                </div>

              

                <div class="text-center">
                    <input type="submit" value="Add" class="btn btn-outline-primary col-md-4">
                </div>
                {{ Form::close() }}
            <br><br>
        </div>
    </div>
    <br>
</div>
</div>

<script>

$('.date').datepicker({  
  format: 'yyyy-mm-dd',
multidate: true,
clearBtn: true,
orientation:'auto',
autoclose: false,
}); 

</script>
@endsection