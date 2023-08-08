@extends('admin.adminlayouts.master')
@section('title', '| Manage Forms')
@section('content')
<div class="table-responsive">
<div class="col-xl-12">
   <div class="card">
      <div class="card-header">
         <h3 class="text-info">
         <i class="feather icon-clipboard"></i> Forms
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
                     <th>Title</th>
                     <th>Status</th>
                     <th>Start</th>
                     <th>End</th>
                     <th>Created_at</th>
                     <th>Actions</th>
                  </tr>
               </thead>
               <tbody>
                    @foreach($forms as $form)
                     <tr>
                        <td>{{$form->id}}</td>
                        <td><span class="text-uppercase"><b>{{$form->title}}</b></span></td>
                        
                        <td>
                           @if($form->status==0)   
                              <span class="badge bg-secondary text-white">INACTIVE</span>
                           @else
                              <span class="badge bg-success text-white">ACTIVE</span>
                           @endif                        
                        </td>

                        <td>
                           @if($form->start_date==null)
                              <span class="small">No Entry</span>
                           @else
                              <span class="small">{{$form->start_date}}</span>
                           @endif
                        </td>
                        <td>
                           @if($form->end_date==null)
                              <span class="small">No Entry</span>
                           @else
                              <span class="small">{{$form->end_date}}</span>
                           @endif
                        </td>
                        <td><span class="small">{{date('F d, Y', strtotime($form->created_at))}}<span></td>

                        <td>
                           @if($form->status==0)   
                              <a href="" id="publish_form_btn" data-toggle="modal" data-target='#publish_form_modal' data-id="{{ $form->id }}" class="btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="START Form"><span class="pcoded-micon"><i class="fa fa-paper-plane"></i></span><span class="pcoded-mtext"></span></a>
                           @else
                              <a href="#" id="unpublish_form_btn" data-toggle="modal" data-target='#unpublish_form_modal' data-id="{{ $form->id }}" class="btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="END Form"><span class="pcoded-micon"><i class="fa fa-minus-circle"></i></span><span class="pcoded-mtext"></span></a>
                           @endif 
                              <a href="{{ route('forms.show', $form->id) }}" class="btn-sm btn-warning" data-toggle="tooltip" data-placement="top" title="VIEW Form"><span class="pcoded-micon"><i class="fa fa-eye"></i></span><span class="pcoded-mtext"></span></a>
                              <a href="/vote-results/{{$form->id}}" class="btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="DOWNLOAD Responses"><span class="pcoded-micon"><i class="fa fa-download"></i></span><span class="pcoded-mtext"></span></a>
                              <a href="#" id="archive_form_btn" data-toggle="modal" data-target='#archive_form_modal' data-id="{{ $form->id }}" class="btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="ARCHIVE Form"><span class="pcoded-micon"><i class="fa fa-archive"></i></span><span class="pcoded-mtext"></span></a>
                        </td>
                     </tr>
                     @endforeach
               </tbody>
            </table>

            @if ($forms instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $forms->render() }}
            @endif   
         
         </div>
      </div>
   </div>
</div>

<!-- Prompt are u sure modal start PUBLISH FORM -->
   <div class="modal fade" id="publish_form_modal">
      <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
            <div class="modal-body">
               <form method="post" action="{{ url('/start-elections') }}">
                  @csrf
                  <input type="hidden" id="publish_election_form_id" name="id" value="">
                  <center><h1 class="text-primary"><i class="fa fa-paper-plane"></i></h1><center>
                  <center><h3 class="text-primary"><b>Start Election ?</b></h3><center>
                  <center><p>Please confirm that all categories related to this form are in order. <br>Publishing this form will notify all users that voting<br> has started.</p><center>
                  <button type="submit" class="btn btn-success"><i class="feather icon-thumbs-up "></i><span class="pcoded-mtext"> Yes, PUBLISH FORM</span></button>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="feather icon-thumbs-down"></i> No, cancel</button>
               </form>
            </div>
         </div>
      </div>
   </div>
<!-- Prompt are u sure modal end -->

<!-- Prompt are u sure modal start UNPUBLISH FORM-->
   <div class="modal fade" id="unpublish_form_modal">
      <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
            <div class="modal-body">
               <form method="post" action="{{ url('/end-elections') }}">
                  @csrf
                  <input type="hidden" id="unpublish_election_form_id" name="id" value="">
                  <center><h1 class="text-danger"><i class="fa fa-minus-circle"></i></h1><center>
                  <center><h3 class="text-danger"><b>Close Election ?</b></h3><center>
                  <center><p>Please confirm that all user has successfully submitted their votes. <br>Closing this form will terminate the voting process immediately.</p><center>
                  <button type="submit" class="btn btn-success"><i class="feather icon-thumbs-up "></i><span class="pcoded-mtext"> Yes, END FORM</span></button>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="feather icon-thumbs-down"></i> No, cancel</button>
               </form>
            </div>
         </div>
      </div>
   </div>
<!-- Prompt are u sure modal end -->

<!-- Prompt are u sure modal start ARCHIVE FORM-->
   <div class="modal fade" id="archive_form_modal">
      <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
            <div class="modal-body">
               <form method="post" action="{{ url('/archive-elections') }}">
                  @csrf
                  <input type="hidden" id="archive_election_form_id" name="id" value="">
                  <center><h1 class="text-secondary"><i class="fa fa-archive"></i></h1><center>
                  <center><h3 class="text-secondary"><b>Archive Form ?</b></h3><center>
                  <center><p>By archiving, this form will be hidden from your dashboard <br>but <b>will NOT be deleted</b>. Are you sure?</p><center>
                  <button type="submit" class="btn btn-success"><i class="feather icon-thumbs-up "></i><span class="pcoded-mtext"> Yes, MOVE TO ARCHIVE</span></button>
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