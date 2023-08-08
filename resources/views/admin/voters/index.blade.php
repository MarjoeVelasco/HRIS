@extends('admin.adminlayouts.master')
@section('title', '| Manage Voters')
@section('content')
<div class="table-responsive">
<div class="col-xl-12">
   <div class="card">
      <div class="card-header">

         <h3 class="text-info">
         <i class="feather icon-bookmark"></i> List of Voters
            <section class="float-right">

            </section>
         </h3>
      </div>
      @include('inc.messages')
      <div class="card-block table-border-style">
         <div class="table-responsive">
            <table class="table table-hover table-striped" id="forms_table">
               <thead>
                  <tr>
                     <th>No.</th>
                     <th>Ballot No.</th>
                     <th>Form</th>
                     <th>Name</th>
                     <th>Actions</th>
                  </tr>
               </thead>
               <tbody>
               @foreach($voters as $voter)
                    <tr>
                        <td><span class="small">{{ $loop->iteration }}.</span></td>
                        <td class="align-middle"><span class="h5 text-dark"><b>{{ $voter->ballot_number }}</b></span></td>
                        <td class="align-middle"><b>{{$voter->title}}</b></td>
                        <td class="align-middle"><img src="{{ $voter->image }}" height="40px" class="rounded"> <span class="text-uppercase"><b>{{ $voter->lastname }}, {{ $voter->firstname }}</b></span></td>
                        <td class="align-middle">
                            @if($voter->status==0)
                            <a href="" id="remove_voter_btn" data-toggle="modal" data-target='#remove_voter_modal' data-id="{{ $voter->id }}" class="btn-sm btn-danger"><span class="pcoded-micon"><i class="fa fa-user-times"></i></span><span class="pcoded-mtext"> <b>REMOVE</b></span></a>
                            @endif
                        </td>
                    </tr>
               @endforeach
               </tbody>
            </table>


         
         </div>
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