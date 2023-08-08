@extends('admin.adminlayouts.master')
@section('title', '| Election Dashboard')
@section('content')


<div class="row">
   <div class="col-lg-8 col-md-12 ">
      <div class="card h-100">
         <div class="card-header">
            <p class="text-info h4 font-weight-bold"><i class="feather icon-layout"></i> Votes Per Position</p>
         </div>
         <div class="card-body">
            <div id="bar-chart-voters"></div>
         </div>
      </div>
   </div>
   
   <div class="col-lg-4 col-md-12">
      <div class="card">
         <div class="card-header bg-success text-white">
            <div class="row">
               <div class="col-4"><p class="h3"><i class="fa fa-handshake"></i></p></div>
               <div class="col-8"><p class="text-right h3 mb-0 font-weight-bold">{{$yes}}%</p><span class="float-right">Participation ({{$voted}})</span></div>
            </div>
         </div>
      </div>

      <div class="card">
         <div class="card-header bg-info text-white">
            <div class="row">
               <div class="col-4"><p class="h3"><i class="fa fa-users"></i></p></div>
               <div class="col-8"><p class="text-right h3 mb-0 font-weight-bold">{{$number_voters}}</p><span class="float-right">Voters</span></div>
            </div>
         </div>
      </div>

      <div class="card">
         <div class="card-header text-white" style="background:#A020F0">
            <div class="row">
               <div class="col-4"><p class="h3"><i class="fa fa-check-circle"></i></p></div>
               <div class="col-8"><p class="text-right h3 mb-0 font-weight-bold">{{$voted}}</p><span class="float-right">Vote Casted</span></div>
            </div>
         </div>
      </div>

      <div class="card">
         <div class="card-header bg-warning text-white">
            <div class="row">
               <div class="col-4"><p class="h3"><i class="fa fa-window-close"></i></p></div>
               <div class="col-8"><p class="text-right h3 mb-0 font-weight-bold">{{$not_yet}}</p><span class="float-right">Not Yet Voted</span></div>
            </div>
         </div>
      </div>

      

   </div>

   
</div>

<br>
<div class="row">
   <div class="col-lg-7 col-md-12 ">
      <div class="card h-100">
         <div class="card-header">
            <p class="text-info h4 font-weight-bold"><i class="feather icon-settings"></i> ILSEA Quick Links</p>
         </div>
         <div class="card-body">
            <div class="form-group">
            <label class="h6">Election Resolution</label>
            <input type="text" value="https://ilsaam.ils.dole.gov.ph/ilsea/" class="form-control" readonly>
            </div><br>

            <div class="form-group">
            <label class="h6">Election Form</label>
            <input type="text" value="https://ilsaam.ils.dole.gov.ph/ilsea/elections/" class="form-control" readonly>
            </div><br>

            <div class="form-group">
            <label class="h6">Election Live Results</label>
            <input type="text" value="https://ilsaam.ils.dole.gov.ph/live-results/1/" class="form-control" readonly>
            </div>
         </div>
      </div>
   </div>
   
   <div class="col-lg-5 col-md-12">
      <div class="card h-100">
         <div class="card-header">
            <p class="text-info h4 font-weight-bold"><i class="feather icon-trending-up"></i> Voters Percentage</p>
         </div>
         <div clas="card-body">
            <div id="pie-chart-voters"></div>
         </div>
      </div>
   </div>
</div>
<br>
<div class="row">
   <div class="card col-md-12">
      <div class="card-header">
        <b>Voters for</b>
         <h3 class="text-info font-weight-bold">
         <i class="feather icon-clipboard "></i> {{$title}}
            <section class="float-right">
            </section>
         </h3>
      </div>
      <div class="card-block table-border-style">
         <div class="table-responsive">
            <table class="table table-hover table-striped" id="forms_table">
               <thead>
                  <tr>
                     <th>No.</th>
                     <th>Ballot No.</th>
                     <th>Name</th>
                     <th>Remarks</th>
                  </tr>
               </thead>
               <tbody>
               @foreach($voters as $voter)
                    <tr>
                        <td><span class="small">{{ $loop->iteration }}.</span></td>
                        <td class="align-middle"><span class="h5 text-dark"><b>{{ $voter->ballot_number }}</b></span></td>
                        <td class="align-middle"><img src="{{ $voter->image }}" height="40px" class="rounded"> <span class="text-uppercase"><b>{{ $voter->lastname }}, {{ $voter->firstname }}</b></span></td>
                        <td class="align-middle">
                        @if($voter->vote_casted)
                           <span class="badge bg-success text-white p-2"><i class="fa fa-check-circle"></i> VOTE CASTED</span>
                        @else
                           <span class="h4 badge bg-secondary text-white p-2"><i class="fa fa-exclamation-circle"></i> NOT YET VOTED</span>
                        @endif
                        </td>
                    </tr>
               @endforeach
               </tbody>
            </table>   
         </div>
      </div>


   </div>



<!-- Prompt are u sure modal start REMOVE Voter-->
<div class="modal fade" id="remove_voter_modal">
      <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
            <div class="modal-body">
               <form method="post" action="/remove-voter">
                  @csrf
                  <input type="hidden" id="delete_voter_id" name="id" value="">
                  <center><h1 class="text-danger"><i class="fa fa-trash"></i></h1><center>
                  <center><h3 class="text-danger"><b>Delete Voter ?</b></h3><center>
                  <center><p>Are you sure you want to remove this voter?<br>The voter won't be able to cast their votes.</p><center>
                  <button type="submit" class="btn btn-success"><i class="feather icon-thumbs-up "></i><span class="pcoded-mtext"> Yes, REMOVE</span></button>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="feather icon-thumbs-down"></i> No, cancel</button>
               </form>
            </div>
         </div>
      </div>
   </div>
<!-- Prompt are u sure modal end -->







<script>
    //  $('#admin_leaves').DataTable();

      $('#forms_table').DataTable({
      paging: false,
      bInfo : false,
      "order": [ 0, 'desc' ], 
   });

</script>

@endsection