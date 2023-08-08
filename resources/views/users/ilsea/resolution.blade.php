@extends('users.userslayouts.master')
@section('content')
@section('title', 'ILS-EA Resolution')

<div class="container d-flex justify-content-center mt-2">
  <div class="card col-md-10" style="padding-left:0;padding-right:0;">
    <h3 class="bg-info card-header text-center" style="color:white">
      <i class="feather icon-clipboard"></i> Resolution No.2 - 2023
    </h3>

      <div class="card-body">
        <div class="table-responsive">  
          @include('inc.messages')
       
          <iframe src="{{ url('/images/ilsea/ILS-EA Elections Commitee Resolution 04-2023.pdf') }}" width="100%" height="700px"> </iframe>
            

          <br><br>              
          <div class="form-check">            
            <input class="form-check-input" type="checkbox" value="" id="checkbox_resolution">
            <label class="form-check-label" for="checkbox_resolution"> I Agree to the <a href="{{ url('/images/ilsea/ILSEA Elections Commitee Resolution 2-2023.pdf') }}" download>Rules on the Conduct of 2023 General Elections</a></label>
            <a href="{{URL::to('/ilsea/elections',$form->id)}}" id="vote_proceed_btn" class="btn text-white float-right" style="background:#A020F0"><span class="pcoded-micon"><i class="feather icon-arrow-right"></i></span><span class="pcoded-mtext"> Proceed</span></a>
          </div>


        </div>
      </div>
  </div>
</div>


@stop