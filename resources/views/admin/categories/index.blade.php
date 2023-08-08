@extends('admin.adminlayouts.master')
@section('title', '| Manage Categories')
@section('content')
<div class="table-responsive">
<div class="col-xl-12">
   <div class="card">
      <div class="card-header">
         <h3 class="text-info">
         <i class="feather icon-bookmark"></i> Categories
            <section class="float-right">

            </section>
         </h3>
      </div>
      @include('inc.messages')
      <div class="card-block table-border-style">
         <div class="table-responsive">
            @foreach($categories as $category)
            
                  {{ $loop->iteration }}. <span class="h6 text-dark"><b>{{$category->title}}</b> 
                  <a href="{{ route('categories.edit', $category->id) }}"><span class="badge bg-warning text-white"><i class="feather icon-edit"></i></span></a> 
                  </span> <br>
                  <div class="small text-justify" style="white-space: pre-wrap">{{$category->description}}</div>
                  <hr class="bg-primary border-2 border-top border-primary">
            
            @endforeach
           
         </div>
      </div>
   </div>
</div>




@endsection