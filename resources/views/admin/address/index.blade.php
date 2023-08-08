@extends('admin.adminlayouts.master')
@section('title', '| ILS Public IP Address')
@section('content')
<div class="table-responsive">
<div class="col-xl-12">
   <div class="card">
      <div class="card-header">
         <h3 class="text-info">
         <i class="feather icon-bookmark"></i> ILS Public IP Address
          <section class="float-right">
            <a href="/address/create" class="btn btn-outline-primary"><i class="feather icon-bookmark"></i>Add IP Address</a>
          </section>
         </h3>
      </div>
      @include('inc.messages')
      <div class="card-block table-border-style">
        <div class="card-block table-border-style">
          <div class="table-responsive">
            <table class="table table-hover table-striped" id="address_table">
              <thead>
                <tr>
                  <th>IP Address</th>
                  <th>Actions</th>
                </tr>
               </thead>

              <tbody>
                @foreach($address as $ip)
                <tr>
                  <td><span>{{$ip->address_name}}</span></td>
                  <td>

                  <form action="/address/{{$ip->id}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn-xsm btn-danger" data-toggle="tooltip" data-placement="top" title="Delete IP Address">Delete</button>               
                  </form>
                  </td>
                </tr>
                @endforeach
              </tbody>

            </table>
          </div>
        </div>
      </div>
   </div>
</div>



<script>
    //  $('#admin_leaves').DataTable();

      $('#address_table').DataTable({
      paging: false,
      bInfo : false,
      "order": [ 0, 'desc' ], 
   });

</script>


@endsection