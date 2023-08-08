@extends('users.userslayouts.master')
@section('content')
@section('title', 'ILS-EA Resolution')

<div class="container d-flex justify-content-center mt-2">
  <div class="card col-md-9" style="padding-left:0;padding-right:0;">
    <h3 class="bg-info card-header text-center" style="color:white">
      <i class="feather icon-clipboard"></i> Cast your vote!
    </h3>

      <div class="card-body">
        <div class="table-responsive">  
          @include('inc.messages')

                <small style="float:right;"><b>Ballot #{{$voters->ballot_number}}</b></small>
                <img src="{{ url('/images/icons/ilsea_header.png') }}" class="mx-auto d-block" height="50px">
                <p class="h7 text-dark text-center mb-0"><b>Committee on Elections</b></p>
                <hr>

                <p class="h7 text-dark text-center"><b>{{$forms->title}}</b></p> 
                
                <p class="text-center" style="font-size:11px;">{{$forms->description}}</p>
                <br>
                <div class="p-3">

                    <!-- President -->
                    <form method="post" action="/submit-vote" id="submit_vote_form">
                    @csrf
                    <input type="hidden" value="{{$treasurer->id}}" name="treasurer_id">
                    <span class="h6 text-dark">1. <b>{{$treasurer->title}}</b> </span> <br>
                    <div class="small text-justify" style="white-space: pre-wrap">{{$treasurer->description}}</div>
                    <div class="form-group px-5"><br>
                    <select class="form-control @error('treasurer') is-invalid @enderror" name="treasurer" id="treasurer_candidate_dropdown">
                        <option disabled selected value>-- Select Candidate --</option>
                        @foreach($treasurer_candidate as $candidate)
                          <option value="{{$candidate->id}}" @if(old('treasurer') == $candidate->id) selected @endif >{{$candidate->lastname}}, {{$candidate->firstname}} </option>
                        @endforeach
                    </select>
                      @error('treasurer')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror      
                    
                 ***
                 <a href="#" id="confirm_vote_btn" data-toggle="modal" data-target='#confirm_vote_modal' data-id="" class="btn-lg btn-success float-right" data-toggle="tooltip" data-placement="top" title="Submit Vote"><span class="pcoded-micon"><i class="fa fa-paper-plane"></i> Submit</span><span class="pcoded-mtext"></span></a>
                 <br><br>
                 </div>
    
                
<!-- Prompt are u sure modal start VOTE CONFIRMATION-->
<div class="modal fade" id="confirm_vote_modal">
      <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
         <div class="modal-header panel-heading bg-info">
                     <p class="text-center"><h4 class="modal-title text-white"><b>VOTER CONFIRMATION</b></h4></p>
                  </div>
            <div class="modal-body">
              <p class="text-center">Ballot Summary</p>
               

                  <div class="row text-dark">
                    <div class="col text-right" >
                      <p><input type="text" value="Treasurer" style="text-align:right;" readonly="readonly" class="border-0"></p>
                    </div>

                    <div class="col text-uppercase" >
                      <p><input type="text" id="treasurer_text" value="" readonly="readonly" class="border-0 w-100"></p>
                    </div>

                  </div>

                  <p class="text-center small">Please make sure all <br>entries are correct. This action cannot be undone.</p>

                  <hr>
                  
                  <center>
                  
                  <div class="fa-3x">
                    <i class="fas fa-spinner fa-spin" id="spinner_icon"></i>
                    <button type="submit" id="submit_vote_btn" class="btn btn-primary"><i class="feather icon-thumbs-up"></i>Confirm</button>
                    <button type="button" id="cancel_vote_confirm_btn" class="btn btn-secondary" data-dismiss="modal"><i class="feather icon-x"></i>Cancel</button>
                  </div>
  
                  </center> 
              
            </div>
         </div>
      </div>
   </div>
<!-- Prompt are u sure modal end -->
                
                
                </form>
              </div>
        </div>
      </div>
  </div>
</div>












@stop