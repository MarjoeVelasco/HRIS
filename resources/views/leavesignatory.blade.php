@extends('users.userslayouts.master_public')
@section('title', 'Approve Leave')
@section('content')

@if($type=="leave")
<p class="font-weight-bold text-center mt-5 h3 text-dark">Leave request awaiting your approval</p>
@else
<p class="font-weight-bold text-center mt-5 h3 text-dark">ILC/CTO request awaiting your approval</p>
@endif
<p class="text-center mt-2 h7"><span class="font-weight-bold text-primary">{{$employee_name}}</span> has submitted a <i>{{$leave_type}}</i> request.</p>
<div class="container d-flex justify-content-center mt-0">

<div class="card col-sm-12 col-md-8 col-lg-7" style="padding:0;">
   <div class="card-body pt-3">
      @foreach($leaves as $leave)
      <div class="col">
         <div class="col-12 text-center">

            <div class="form-group row">
               <label class="col-sm-5 col-form-label text-right label_form">Name :</label>
               <div class="col-sm-7">
                  <input type="text" readonly class="form-control-plaintext" value='{{$employee_name}}'>
               </div>
            </div>

            <div class="form-group row">
               <label class="col-sm-5 col-form-label text-right label_form">Position :</label>
               <div class="col-sm-7">
                  <input type="text" readonly class="form-control-plaintext" value='{{$leave->position}}'>
               </div>
            </div>

            <div class="form-group row">
               <label class="col-sm-5 col-form-label text-right label_form">Date of Filing :</label>
               <div class="col-sm-7">
                  <input type="text" readonly class="form-control-plaintext" value="{{date('F d, Y h:m a', strtotime($leave->created_at))}}">
               </div>
            </div>

            <div class="form-group row">
               <label class="col-sm-5 col-form-label text-right label_form">Type of Leave :</label>
               <div class="col-sm-7">
                  <input type="text" readonly class="form-control-plaintext" value='{{$leave->leave_type}}'>
               </div>
            </div>

            <div class="form-group row">
               <label class="col-sm-5 col-form-label text-right label_form">Hours/Days applied :</label>
               <div class="col-sm-7">
                  <input type="text" readonly class="form-control-plaintext" value="{{$leave->no_days}} day/s">
               </div>
            </div>

            <div class="form-group row">
               <label class="col-sm-5 col-form-label text-right label_form">Date of Availment : </label>
               <div class="col-sm-7">
                  <input type="text" readonly class="form-control-plaintext" value="{{$leave->inclusive_dates}}">
               </div>
            </div>

            @if($leave->reason!="n/a")
            <div class="form-group row">
               <label class="col-sm-5 col-form-label text-right label_form">Reason for late filing : </label>
               <div class="col-sm-7">
                  <input type="text" readonly class="form-control-plaintext" value="{{$leave->reason}}">
               </div>
            </div>
            @endif

            <div class="form-group row">
               <label class="col-sm-5 col-form-label text-right label_form">Leave Form:</label>
               <div class="col-sm-7 text-left">
                  <a href="/download/{{$type}}/{{ $encrypted_leave_id }}" target="_blank" class="btn-sm btn-success"><span class="pcoded-micon"><i class="feather icon-download"></i></span><span class="pcoded-mtext"> Download</span></a>
               
                  <!-- if leave has attachment start -->
                  @if($leave->attachment!=null)
                     <a href="{{ request()->getSchemeAndHttpHost() }}/{{$leave->attachment}}" data-toggle="tooltip" data-placement="top" title="Download Attachment" class="btn-sm btn-primary" download><span class="pcoded-micon"><i class="feather icon-paperclip"></i></span><span class="pcoded-mtext"></span></a>
                  @endif
                  <!-- end -->

               </div>
            </div>

            @if($leave_type == "cto")
            <label class="col-form-label text-right label_form">ILC/COC Source/Origin/Particular :</label>
            <div class="text-center form-control-plaintext" style="white-space: pre-wrap;font-weight:bold;">{{$leave->particulars}}</div>
            <br>
            @endif

            @if($user_role=="secondary")
               
               <a href="" data-toggle="modal" data-target='#approve_secondary_modal' class="btn btn-primary"><span class="pcoded-micon"><i class="feather icon-thumbs-up"></i></span><span class="pcoded-mtext"> Approve</span></a>

               <!-- Prompt are u sure modal start -->
               <div class="modal fade" id="approve_secondary_modal">
                        <div class="modal-dialog modal-dialog-centered">
                           <div class="modal-content">
                                 <div class="modal-body" id="cancel_modal_body">
                                 <center><h1 class="text-warning"><i class="feather icon-alert-triangle"></i></h1><center>
                                 <center><h3>Approve Leave Request?</h3><center>
                                 <center><p>Please make sure all leave detail entries are correct. <br>This will be forwarded to the next signatory for review<br> and approval.</p><center>
                                 <a href="/signatory-approve/{{$type}}/secondary/{{$encrypted_user_id}}/{{$encrypted_leave_id}}" class="btn btn-success"><span class="pcoded-micon"><i class="feather icon-thumbs-up"></i></span><span class="pcoded-mtext"> Yes, CONFIRM</span></a>
                                 <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="feather icon-thumbs-down"></i>No, cancel</button>
                                 </div>
                           </div>
                        </div>
               </div>
               <!-- Prompt are u sure modal end -->
               <!-- 
               <a href="/signatory-decline/leave/secondary/{{$encrypted_user_id}}/{{$encrypted_leave_id}}" class="btn btn-danger"><span class="pcoded-micon"><i class="feather icon-thumbs-down"></i></span><span class="pcoded-mtext"> Disapprove</span></a>
               -->
               <a href="" id="cancelLeave" data-toggle="modal" data-target='#cancel_modal' class="btn btn-danger"><span class="pcoded-micon"><i class="feather icon-x"></i></span><span class="pcoded-mtext"> Disapprove</span></a>
            @endif

            @if($user_role=="primary")

               <a href="" data-toggle="modal" data-target='#approve_secondary_modal' class="btn btn-primary"><span class="pcoded-micon"><i class="feather icon-thumbs-up"></i></span><span class="pcoded-mtext"> Approve</span></a>

               <!-- Prompt are u sure modal start -->
               <div class="modal fade" id="approve_secondary_modal">
                        <div class="modal-dialog modal-dialog-centered">
                           <div class="modal-content">
                                 <div class="modal-body" id="cancel_modal_body">
                                 <center><h1 class="text-warning"><i class="feather icon-alert-triangle"></i></h1><center>
                                 <center><h3>Approve Leave Request?</h3><center>
                                 <center><p>Are you sure you want to approve this leave request?<br>Please make sure all leave detail entries are correct.</p><center>
                                 <a href="/signatory-approve/{{$type}}/primary/{{$encrypted_user_id}}/{{$encrypted_leave_id}}" class="btn btn-success"><span class="pcoded-micon"><i class="feather icon-thumbs-up"></i></span><span class="pcoded-mtext"> Yes, CONFIRM</span></a>
                                 <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="feather icon-thumbs-down"></i>No, cancel</button>
                                 </div>
                           </div>
                        </div>
               </div>
               <!-- Prompt are u sure modal end -->

               <!--
               <a href="/signatory-decline/leave/primary/{{$encrypted_user_id}}/{{$encrypted_leave_id}}" class="btn btn-danger"><span class="pcoded-micon"><i class="feather icon-thumbs-down"></i></span><span class="pcoded-mtext"> Disapprove</span></a>
               -->
               <a href="" id="cancelLeave" data-toggle="modal" data-target='#cancel_modal' class="btn btn-danger"><span class="pcoded-micon"><i class="feather icon-x"></i></span><span class="pcoded-mtext"> Disapprove</span></a>

            @endif
         </div>
         <br>
         <small class="text-align:justify;">Disclaimer: By using this platform, you are giving consent to affix your e-signature along with your employee information such as name, position, and email which will be used solely in processing this leave application.</small>

      </div>
   </div>
</div>


<!-- Modal for cancel -->
<div class="modal fade" id="cancel_modal">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-body" id="cancel_modal_body">
         <center>
            <h1 class="text-danger"><i class="feather icon-trash-2"></i></h1>
            <h3>Are you sure?</h3>
            <p>Do you really want to disapprove this leave request? <br>This process cannot be undone.</p>
            <form method="post" action="/signatory-decline/{{$type}}/{{$user_role}}">
            @csrf
               <input type="hidden" value='{{$encrypted_user_id}}' name="encrypted_user_id_input">
               <input type="hidden" value='{{$encrypted_leave_id}}' name="encrypted_leave_id_input">
               <div class="form-group">
                  <textarea class="form-control" placeholder="Cite reasons for disapproval (optional)" name="reason"></textarea>
               </div>
               <input type="submit" class="btn btn-danger" value="Confirm"><button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </form>
         <center>
         </div>
      </div>
   </div>
</div>
<!-- End -->





@endforeach
<style>
   @media only screen and (max-width: 576px) {
  .label_form {
    text-align:left !important;
  }
}
</style>


@stop

