@extends('admin.adminlayouts.master')
@section('title', '| Certify Leave Credits')
@section('content')
<div class="table-responsive">
<div class="col-xl-12">
   <div class="card">
      <div class="card-header">
         <h3 class="text-info">
            <i class="fa fa-clipboard"></i> Review CTO Request
            <section class="float-right">
               <a href="/supervise-general-cto" class="btn btn-outline-warning"><i class="feather icon-arrow-left"></i></span><span class="pcoded-mtext"> Go Back</span></a>
            </section>
         </h3>
      </div>
      @include('inc.messages')
      <div class="card-block table-border-style">
      @foreach($leaves as $leave)


         <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6">
            <h5>Employee Details:</h5>
                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-5 col-form-label">Name :</label>
                        <div class="col-sm-7">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value='{{$employee_name}}'>
                        </div>
                </div>

                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-5 col-form-label">Position :</label>
                        <div class="col-sm-7">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value='{{$leave->position}}'>
                        </div>
                </div>

                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-5 col-form-label">Date of Filing :</label>
                        <div class="col-sm-7">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{date('F d, Y h:m a', strtotime($leave->created_at))}}">
                        </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-6">
            <h5>Leave Details:</h5>
                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-5 col-form-label">Type of Leave :</label>
                        <div class="col-sm-7">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value='{{$leave->leave_type}}'>
                        </div>
                </div>

                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-5 col-form-label">Hours/Days applied :</label>
                        <div class="col-sm-7">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{$leave->no_days}} day/s">
                        </div>
                </div>

                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-5 col-form-label">Date of Availment : </label>
                        <div class="col-sm-7">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{$leave->inclusive_dates}}">
                        </div>
                </div>

                @if($leave->reason!="n/a")
                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-5 col-form-label">Reason for late filing: </label>
                        <div class="col-sm-7">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{$leave->reason}}">
                        </div>
                </div>
                @endif

                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-5 col-form-label">Leave Form:</label>
                        <div class="col-sm-7">
                        <a href="/download-cto/{{$leave->id}}" class="btn-sm btn-success"><span class="pcoded-micon"><i class="feather icon-download"></i></span><span class="pcoded-mtext"> Download here</span></a>
                        
                        <!-- if leave has attachment start -->
                        @if($leave->attachment!=null)
                                    <a href="{{ request()->getSchemeAndHttpHost() }}/{{$leave->attachment}}" data-toggle="tooltip" data-placement="top" title="Download Attachment" class="btn-sm btn-primary" download><span class="pcoded-micon"><i class="feather icon-paperclip"></i></span><span class="pcoded-mtext"></span></a>
                        @endif
                        <!-- end -->
                    
                        </div>
                </div>               
                <span class="text-dark">ILC/COC Source/Origin/Particular :</span> <textarea name="cto_particulars" class="form-control" placeholder="e.g 2020 PRAISE Award - Best Supervisor" required readonly>{{$leave->particulars}}</textarea>
 
            </div>


            <div class="col-12 mt-3" id="hr_disapprove_reason">
                <form method="post" action="/disapprove-cto-supervisor">
                @csrf
                
                    <input type="hidden" value="{{$leave->id}}" name="leave_id">
                    <div class="form-group">
                        <label">Reason for disapproval:</label>
                        <textarea name="reason" class="form-control" maxlength="320" rows="5" placeholder="optional"></textarea>
                    </div>

                    <button type="submit" class="btn btn-danger" id="approve_leave_btn"><i class="feather icon-thumbs-down "></i><span class="pcoded-mtext"> Confirm</span></button>
                </form>
            </div>

        </div>

        <a href="" data-toggle="modal" data-target='#update_credits_modal' class="btn btn-success"><span class="pcoded-micon"><i class="feather icon-save"></i></span><span class="pcoded-mtext"> Approve</span></a>

         <!-- Prompt are u sure modal start -->
         <div class="modal fade" id="update_credits_modal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body" id="cancel_modal_body">
                        <center><h1 class="text-warning"><i class="feather icon-alert-triangle"></i></h1><center>
                        <center><h3>Approve Leave Request?</h3><center>
                        <center><p>Please make sure all leave detail entries are correct. <br>This will be forwarded to the next signatory for review<br> and approval.</p><center>
                        <a href="/approve-cto-supervisor/{{$leave->id}}" class="btn btn-primary" id="approve_cto_btn"><i class="feather icon-thumbs-up "></i><span class="pcoded-mtext"> Yes, CONFIRM</span></a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="feather icon-thumbs-down"></i> No, cancel</button>
                        </div>
                    </div>
                </div>
        </div>
        <!-- Prompt are u sure modal end -->

        <button class="btn btn-danger" id="decline_cto_btn"><i class="feather icon-thumbs-down "></i><span class="pcoded-mtext"> Decline</span></button>


           


            @endforeach
         </div>
      </div>
   </div>
</div>



<!-- Disclaimer modal start -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel"><i class="feather icon-shield"></i> E-Signature Consent</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         
        </button>
      </div>
      <div class="modal-body">
        <p class="text-justify">By approving this leave request, you are giving consent to affix your electronic signature along with your employee information such as name, and position to this Civil Service Form No. 6 (Leave Request Form)</p>
 
        <p class="text-justify">Rest assured that any information divulged will be treated with utmost confidentiality 
 within the terms and scope of the <a target="_blank" href="https://www.privacy.gov.ph/data-privacy-act/">Data Privacy Act of 2012</a> and will be used solely
 in processing this leave application.</p>

      </div>
      <div class="modal-footer">
         <div class="row d-flex justify-content-center">
            <button type="button" class="btn btn-success" data-dismiss="modal"><i class="feather icon-thumbs-up"></i> Yes -- I Consent</button>
            <a href="/home"><button type="button" class="btn btn-danger"><i class="feather icon-thumbs-down"></i> NO, I DO NOT CONSENT</button></a>
         </div>   
      </div>
    </div>
  </div>
</div>
<!-- Disclaimer modal end-->


@endsection