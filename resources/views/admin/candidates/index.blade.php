@extends('admin.adminlayouts.master')
@section('title', '| Manage Voters')
@section('content')
<div class="table-responsive">
<div class="col-xl-12">
   <div class="card">
      <div class="card-header">

         <h3 class="text-info">
         <i class="feather icon-bookmark"></i> Candidates
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
                     <th>Name</th>
                     <th>Form</th>
                     <th>Category</th>
                     <th>Actions</th>
                  </tr>
               </thead>
               <tbody>
               @foreach($candidates as $candidate)
                    <tr>
                        <td><span class="small">{{ $loop->iteration }}.</span></td>
                        <td> <img src="{{ $candidate->image }}" height="40px" class="rounded"> {{ $candidate->firstname }} {{ $candidate->lastname }}</td>
                        <td>{{ $candidate->form_title }}</td>
                        <td>{{ $candidate->category_title }}</td>
                        <td><a href="" id="remove_candidate_btn" data-toggle="modal" data-target='#remove_candidate_modal' data-id="{{ $candidate->id }}" class="btn-sm btn-danger"><span class="pcoded-micon"><i class="fa fa-user-times"></i></span><span class="pcoded-mtext"> <b>REMOVE</b></span></a></td>
                    </tr>
               @endforeach
               </tbody>
            </table>

            @if ($candidates instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $candidates->render() }}
            @endif   

         
         </div>
      </div>
   </div>
</div>


<!-- Prompt are u sure modal start REMOVE Voter-->
<div class="modal fade" id="remove_candidate_modal">
      <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
            <div class="modal-body">
               <form method="post" action="/remove-candidate">
                  @csrf
                  <input type="hidden" id="delete_candidate_id" name="id" value="">
                  <center><h1 class="text-danger"><i class="fa fa-user-times"></i></h1><center>
                  <center><h3 class="text-danger"><b>Delete Candidate ?</b></h3><center>
                  <center><p>Are you sure you want to remove this candidate?<br>This candidate cannot be voted.</p><center>
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

   });

</script>

@endsection