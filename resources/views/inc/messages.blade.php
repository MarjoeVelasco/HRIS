@if(isset($errors))


@if(count($errors) > 0)
    @foreach($errors->all() as $error)
    <div class="alert alert-danger">
        {{$error}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
    </div>
    @endforeach
@endif 

@endif

@if(session('success'))
    <div class="alert alert-success">
        <center>{{session('success')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span></center>
  </button>
    </div>
@endif


@if(session('error'))
    <div class="alert alert-danger">
    <center>{{session('error')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span></center>
  </button>
    </div>
@endif