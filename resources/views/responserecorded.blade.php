@extends('users.userslayouts.master_public')
@section('title', 'Response Recorded')
@section('content')

<div class="container d-flex justify-content-center">

<div class="card col-sm-12 col-md-8 col-lg-6 mt-3" style="padding:0;">
   <div class="card-body mt-3 text-center">
      <br>
   <img src="{{url('/images/icons/leave_report.png')}}" height="200" alt="ILS logo header"/>
   <p class="font-weight-bold text-center mt-6 h3 text-dark"><br>Your reponse has been recorded.</p>
   <p>You'll be notified upon completion of this agreement. A copy will be sent through email to all parties involved. You may now close this tab. If you need help with anything, please let one of the admins know immediately. We're here to help you at any step along the way.<br>

   <p>Click <a href="/download/{{$type}}/{{ $encrypted_leave_id }}">here</a> to download a copy of the document.</p>

</p>
   
      </div>
   </div>
</div>






@stop

