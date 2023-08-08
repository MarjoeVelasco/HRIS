@extends('admin.adminlayouts.master')
@section('title', '| Error Log')
@section('content')
<div class="table-responsive">
<div class="col-xl-12">
   <div class="card">
      <div class="card-header">
         <h3 class="text-info">
            <i class="fa fa-cogs"></i> Error Log
            <section class="float-right">
               <a href="/exportlog" class="btn btn-outline-primary"><i class="feather icon-download "></i></span><span class="pcoded-mtext"> Export</span></a>
               <a href="/clearlog" class="btn btn-outline-success"><i class="feather icon-trash"></i></span><span class="pcoded-mtext"> Clear</span></a>
               <a href="/testingmail" class="btn btn-outline-warning"><i class="feather icon-trash"></i></span><span class="pcoded-mtext"> Do not click</span></a>
            </section>
         </h3>
      </div>
      @include('inc.messages')
      <div class="card-block table-border-style">
         <div class="table-responsive">
            
            <textarea style="width:100%" rows="18" readonly>
            @forelse ($error_logs as $error_log)
{{$error_log->created_at}} {{$error_log->message}} {{$error_log->file}} {{$error_log->url}} line {{$error_log->line}} 
            @empty
               no data yet
            @endforelse
            </textarea>
         </div>
      </div>
   </div>
</div>

@endsection