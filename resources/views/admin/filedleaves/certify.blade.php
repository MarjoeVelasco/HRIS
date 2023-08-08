@extends('admin.adminlayouts.master')
@section('title', '| Certify Leave Credits')
@section('content')
<div class="table-responsive">
<div class="col-xl-12">
   <div class="card">
      <div class="card-header">
         <h3 class="text-info">
            <i class="fa fa-clipboard"></i> Certify Leave Credits
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

            


            <!-- if not certified -->
            <div class="col-12 mt-3">
            @if($leave->credits_id==null)
            <span class="h6 text-dark">Certification as of: <span class="text-muted">{{$date_certification}}</span> </span> <span class="badge rounded label label-warning"><i class="feather icon-clock"></i> Not yet certified</span> 
            
            @else
            <!-- if certified -->
            <span class="h6 text-dark">Certified: <span class="text-muted">{{$date_certification}}</span> </span> <span class="badge rounded label label-primary"><i class="feather icon-check"></i> Certified</span> 
    
            @endif

            <form method="post" action="{{action('LeaveCreditsController@store')}}">
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
            @if($leave->internal_notes!="n/a")
                <p class="text-muted"><b>Internal Note: </b>{{$leave->internal_notes}}</p>
            @endif 
            <a href="" data-toggle="modal" data-target='#update_credits_modal' class="btn btn-primary"><span class="pcoded-micon"><i class="feather icon-save"></i></span><span class="pcoded-mtext"> Update</span></a>

            <!-- Prompt are u sure modal start -->
            <div class="modal fade" id="update_credits_modal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body" id="cancel_modal_body">
                        <center><h1 class="text-warning"><i class="feather icon-alert-triangle"></i></h1><center>
                        <center><h3>Certify Leave Credits?</h3><center>
                        <center><p>Please make sure the leave credit entries are correct. <br>This will notify the requestor that their leave request <br>is now being processed.</p><center>
                        <button type="submit" class="btn btn-success"><i class="feather icon-thumbs-up "></i><span class="pcoded-mtext"> Yes, CONFIRM</span></button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="feather icon-thumbs-down"></i> No, cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Prompt are u sure modal end -->

            </form>

            

        </div>

           


            @endforeach
         </div>
      </div>
   </div>
</div>





@endsection