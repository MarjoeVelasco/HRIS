@extends('users.userslayouts.master')
@section('content')
@section('title', 'Vote Confirmation')

<div class="container d-flex justify-content-center mt-2">
  <div class="card col-sm-8 col-md-4 rounded" style="padding-left:0;padding-right:0;">
   

      <div class="card-body">
        <div class="table-responsive">  
          @include('inc.messages')

          @foreach($ballots as $ballot)

            <p class="text-center h3" style='font-weight:800;'>YOUR VOTE COUNTS</p>            
            <p class="text-center"><img src="{{ url('/images/icons/ilsea_logo.png') }}"></p>
            <p class="text-center text-dark"><b>Thank you for voting!</b><br>This is to confirm receipt of your ballot</p>
            <center>
              <small>Verify your ballot at:</small>
              <small>ilsaam.ils.dole.gov.ph/ilsea/elections/ballot</small><br><br> 
              <small>Reference Number:</small><br>
              <input type="number" class="text-center border border-secondary rounded" value="{{$ballot->reference_number}}" readonly><br><br>
              <small>{{date('F d, Y h:i a', strtotime($ballot->created_at))}}</small>
              <br><br>

              <a href="{{URL::to('/live-results',$ballot->form_id)}}" class="btn text-white" style="background:#A020F0"><span class="pcoded-micon"><i class="feather icon-award"></i></span><span class="pcoded-mtext"> VIEW LIVE RESULTS</span></a>
            </center>

            @endforeach

          </div>
    

        </div>
      </div>
  </div>
</div>


@stop