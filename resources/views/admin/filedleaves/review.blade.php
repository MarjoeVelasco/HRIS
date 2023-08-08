@extends('admin.adminlayouts.master')
@section('title', '| Certify Leave Credits')
@section('content')
<div class="table-responsive">
<div class="col-xl-12">
   <div class="card">
      <div class="card-header">
         <h3 class="text-info">
            <i class="fa fa-clipboard"></i> Review Leave Request
            <section class="float-right">
               <a href="/managefiledleaves" class="btn btn-outline-warning"><i class="feather icon-arrow-left"></i></span><span class="pcoded-mtext"> Go Back</span></a>
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
                    <label for="staticEmail" class="col-sm-5 col-form-label">Division :</label>
                        <div class="col-sm-7">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value='{{$division_short}}'>
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
                    <label for="staticEmail" class="col-sm-5 col-form-label">Type of Leave Availed :</label>
                        <div class="col-sm-7">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value='{{$leave_type}}'>
                        </div>
                </div>

                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-5 col-form-label">Number of Days :</label>
                        <div class="col-sm-7">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{$leave->no_days}} day/s">
                        </div>
                </div>

                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-5 col-form-label">Inclusive Dates: </label>
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
                        <a href="/download-leave/{{$leave->id}}" class="btn-sm btn-success"><span class="pcoded-micon"><i class="feather icon-download"></i></span><span class="pcoded-mtext"> Download here</span></a>
                        
                        <!-- if leave has attachment start -->
                        @if($leave->attachment!=null)
                            <a href="{{ request()->getSchemeAndHttpHost() }}/{{$leave->attachment}}" data-toggle="tooltip" data-placement="top" title="Download Attachment" class="btn-sm btn-primary" download><span class="pcoded-micon"><i class="feather icon-paperclip"></i></span><span class="pcoded-mtext"></span></a>
                        @endif
                        <!-- end -->

                        </div>
                </div>
            </div>

            @if($leave->hr_id==Auth::user()->id)
            <!-- if not certified -->
            <div class="col-12 mt-3">
            @if($leave->credits_id==null)
            <span class="h6 text-dark">Certification as of: <span class="text-muted">{{$date_certification}}</span> </span> <span class="badge rounded label label-warning"><i class="feather icon-clock"></i> Not yet certified</span> 
            
            @else
            <!-- if certified -->
            <span class="h6 text-dark">Certified: <span class="text-muted">{{$date_certification}}</span> </span> <span class="badge rounded label label-primary"><i class="feather icon-check"></i> Certified</span> 
    
            @endif

                           
            <form method="post" action="/approve-leave" id="approve_leave_form">
            @csrf
                <input type="hidden" value="{{$leave->id}}" name="leave_id">
                <input type="hidden" value="{{$date_certification}}" name="date_certification">
            <table class="table table-bordered mt-2">
                
                <tr>
                    <th> </th>
                    <th>Vacation Leave</th>
                    <th>Sick Leave</th>
                </tr>

                <tr>
                    <td>Total Earned</td>
                    <td><input type="number" step="any" class="form-control" name="total_earned_vl" id="total_earned_vl" value="{{$total_vl}}" required></td>
                    <td><input type="number" step="any" class="form-control" name="total_earned_sl" id="total_earned_sl" value="{{$total_sl}}" required></td>
                </tr>

                <tr>
                    <td>Less this application</td>
                    <td><input type="number" step="any" class="form-control" name="less_vl" id="less_vl" value="{{$less_vl}}" required></td>
                    <td><input type="number" step="any" class="form-control" name="less_sl" id="less_sl" value="{{$less_sl}}" required></td>
                </tr>

                <tr>
                    <td>Balance</td>
                    <td><input type="text" readonly class="form-control-plaintext" name="balance_vl" id="balance_vl" value="{{$balance_vl}}" required></td>
                    <td><input type="text" readonly class="form-control-plaintext" name="balance_sl" id="balance_sl" value="{{$balance_sl}}" required></td>
                </tr>

            </table>

            <div class="col-12">
            @if($leave->internal_notes!="n/a")
                <div class="form-group row">
                Internal note : <textarea class="form-control" name="internal_notes">{{$leave->internal_notes}}</textarea>
                </div>
                @endif
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
                        <button type="submit" class="btn btn-primary" id="approve_leave_btn"><i class="feather icon-thumbs-up "></i><span class="pcoded-mtext"> Yes, CONFIRM</span></button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="feather icon-thumbs-down"></i> No, cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Prompt are u sure modal end -->


          

            </form>
            <button class="btn btn-danger" id="decline_leave_btn"><i class="feather icon-thumbs-down "></i><span class="pcoded-mtext"> Decline</span></button>
            @endif

        </div>

        <div class="col-12 mt-3" id="hr_disapprove_reason">
                <form method="post" action="/disapprove-leave">
                @csrf
                
                    <input type="hidden" value="{{$leave->id}}" name="leave_id">
                    <div class="form-group">
                        <label">Reason for disapproval:</label>
                        <textarea name="reason" class="form-control" rows="5" placeholder="optional"></textarea>
                    </div>

                    <button type="submit" class="btn btn-danger" id="approve_leave_btn"><i class="feather icon-thumbs-down "></i><span class="pcoded-mtext"> Confirm</span></button>
                </form>
            </div>
           


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