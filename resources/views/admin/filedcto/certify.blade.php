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
               <a href="/managefiledcto" class="btn btn-outline-warning"><i class="feather icon-arrow-left"></i></span><span class="pcoded-mtext"> Go Back</span></a>
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

            </div>

            <div class="col-12 mt-3">
            @if($leave->credits_id==null)
            <span class="h6 text-dark">Certification of ILC/COC earned</span> <span class="badge rounded label label-warning"><i class="feather icon-clock"></i> Not yet certified</span>
            @else
            <span class="h6 text-dark">Certification of ILC/COC earned</span> <span class="badge rounded label label-primary"><i class="feather icon-check"></i> Certified</span> 
            @endif
            <form method="post" action="{{action('CtoCreditsController@store')}}">
            @csrf

            <span class=" text-dark">ILC/COC Source/Origin/Particular :</span> <textarea name="cto_particulars" class="form-control" placeholder="e.g 2020 PRAISE Award - Best Supervisor" required>{{$leave->particulars}}</textarea> 
     

                <input type="hidden" value="{{$leave->id}}" name="leave_id">

                <table class="table table-bordered mt-2">
                    <tr>
                        <td>Certification as of</td>
                        <td><input type="text" readonly class="form-control-plaintext" name="certification_as_of" value="{{$date_certification}}" required></td>
                    </tr>

                    <tr>
                        <td>Number of Hours Earned</td>
                        <td><input type="text" class="form-control" value="{{$hours_earned}}" name="hours_earned" required></td>
                    </tr>

                    <tr>
                        <td>Date of Last Certification</td>
                        <td><input class="form-control" type="text" value="{{$last_certification}}" name="last_certification" required></td>
                    </tr>

                </table>

                <a href="" data-toggle="modal" data-target='#update_credits_modal' class="btn btn-primary"><span class="pcoded-micon"><i class="feather icon-save"></i></span><span class="pcoded-mtext"> Update</span></a>

                <!-- Prompt are u sure modal start -->
                <div class="modal fade" id="update_credits_modal">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body" id="cancel_modal_body">
                            <center><h1 class="text-warning"><i class="feather icon-alert-triangle"></i></h1><center>
                            <center><h3>Certify Leave Credits?</h3><center>
                            <center><p>Please make sure the leave credit entries are correct. <br>This will notify the requestor that their ILC/CTO request <br>is now being processed.</p><center>
                            <button type="submit" class="btn btn-success"><i class="feather icon-thumbs-up "></i><span class="pcoded-mtext"> Yes, CONFIRM</span></button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="feather icon-thumbs-down"></i> No, cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Prompt are u sure modal end -->

            </form>

            </div>


        </div>

           


            @endforeach
         </div>
      </div>
   </div>
</div>

@endsection