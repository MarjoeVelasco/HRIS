@extends('admin.adminlayouts.master')
@section('title', '| Leave Reports')
@section('content')
<div class="table-responsive">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h3 class="text-info"><i class="feather icon-file-text"></i> Leave Report
               
                </h3>
                
            </div>

           

            <div class="card-block table-border-style">
               
                    
                                <h5 class="text-info">General Leaves</h5>
                                <form method="get" action="/exportleave">
                                    @csrf
                                   
                                    <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <label for="example-date-input" class="col-form-label">From</label>
                                        <input class="form-control " type="date"  id="example-date-input" name="from" required="">
                                    </div>
                                   
                                    <div class="form-group col-md-2">    
                                        <label for="example-date-input" class="col-form-label">To</label>
                                        <input class="form-control" type="date"  id="example-date-input" name="to" required="">
                                    </div>

                                    <div class="form-group col-md-3">    
                                        <label for="example-date-input" class="col-form-label">Employee</label>
                                        <select name="id" class="form-control">
                                        <option value='All'>All</option>
                                        @if(isset($users))
                                        @foreach($users as $user)
                                        <option value='{{$user->id}}'>{{$user->name}} | {{$user->email}}</option>
                                        @endforeach
                                        @endif
                                        </select>
                                    </div>
                                    </div>



                                    <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <button class="btn btn-outline-primary" type="submit"><i class="feather icon-download "></i></span><span class="pcoded-mtext"> Generate Report</span></button>
                                   </div>
                                   </div>
                                    
                               
                            </form>

                            <br>
                            <h5 class="text-info">CTO</h5>
                            <form method="get" action="/exportcto">
                                    @csrf
                                   
                                    <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <label for="example-date-input" class="col-form-label">From</label>
                                        <input class="form-control " type="date"  id="example-date-input" name="from" required="">
                                    </div>
                                   
                                    <div class="form-group col-md-2">    
                                        <label for="example-date-input" class="col-form-label">To</label>
                                        <input class="form-control" type="date"  id="example-date-input" name="to" required="">
                                    </div>

                                    <div class="form-group col-md-3">    
                                        <label for="example-date-input" class="col-form-label">Employee</label>
                                        <select name="id" class="form-control">
                                        <option value='All'>All</option>
                                        @if(isset($users))
                                        @foreach($users as $user)
                                        <option value='{{$user->id}}'>{{$user->name}} | {{$user->email}}</option>
                                        @endforeach
                                        @endif
                                        </select>
                                    </div>
                                    </div>



                                    <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <button class="btn btn-outline-primary" type="submit"><i class="feather icon-download "></i></span><span class="pcoded-mtext"> Generate Report</span></button>
                                   </div>
                                   </div>
                                    
                               
                            </form>

                            <hr>
                            <br>
                            <h5 class="text-info">Leaves (Prior to November 13, 2022 TALMS Update)</h5>
                            <form method="get" action="/export-archive">
                                    @csrf
                                   
                                    <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <label for="example-date-input" class="col-form-label">From</label>
                                        <input class="form-control " type="date"  id="example-date-input" name="from" required="">
                                    </div>
                                   
                                    <div class="form-group col-md-2">    
                                        <label for="example-date-input" class="col-form-label">To</label>
                                        <input class="form-control" type="date"  id="example-date-input" name="to" required="">
                                    </div>

                                    <div class="form-group col-md-3">    
                                        <label for="example-date-input" class="col-form-label">Employee</label>
                                        <select name="id" class="form-control">
                                        <option value='All'>All</option>
                                        @if(isset($users))
                                        @foreach($users as $user)
                                        <option value='{{$user->id}}'>{{$user->name}} | {{$user->email}}</option>
                                        @endforeach
                                        @endif
                                        </select>
                                    </div>
                                    </div>



                                    <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <button class="btn btn-outline-primary" type="submit"><i class="feather icon-download "></i></span><span class="pcoded-mtext"> Generate Report</span></button>
                                   </div>
                                   </div>
                                    
                               
                            </form>
                           
                       
               
            </div>
        </div>
    </div>
    <!-- [ Hover-table ] start-->
    
    <!-- [ Hover-table ] end -->
    
    @endsection