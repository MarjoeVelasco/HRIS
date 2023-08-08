@extends('users.userslayouts.master')
@section('title', 'Settings')
@section('content')
<div class="container d-flex justify-content-center  mt-2">
   <div class="card col-md-5" style="padding:0;">


   <h3 class="bg-info card-header text-center" style="color:white">
    <i class="feather icon-unlock"></i> Change Password
        </h3>


            <div class="card-body text-center">
            	<form method="POST" action="{{action('SettingsController@update',Auth::user()->id)}}" enctype="multipart/form-data">
                    @csrf
                                  <input type="hidden" name="_method" value="PATCH">
                
                <input type="hidden" name="id" value="{{ Auth::user()->id}}">

                <div class="input-group mb-4">
                            <input id="old-password" type="password" class="form-control" name="old_password" required autocomplete="new-password" placeholder="Old Password">
                    </div>


                    <div class="input-group mb-3">
                         <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="New Password">
                                @error('new_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                    </div>
                    <div class="input-group mb-4">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                    </div>
            
                    
                    <button class="btn btn-primary mb-4" type="submit">Save</button>
                    
                </form>

               
@include('inc.messages')

            </div>
        </div>

</div>












@stop