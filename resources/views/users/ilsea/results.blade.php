@extends('users.userslayouts.master')
@section('content')
@section('title', 'Vote Confirmation')

<div class="container d-flex justify-content-center mt-2">
@include('inc.messages')
   <div class="card col-sm-8 col-md-9 rounded">
        <p class="mt-3 mb-2 text-uppercase text-dark font-weight-bold text-center" style="font-size:1.6em;">{{$election_title}}</p>
        <hr class="mt-0 mb-0">
            <p class="mt-1 small text-center">Reporting as of <b><span class="text-success">{{$current_date}}</span></b> <b><span id="live_time_results" class="text-success">TIME</span></b>. Unofficial-partial results based on real-time data from the ballot server.</p>
   </div>
</div>

<!-- TREASURER START -->
<div class="container d-flex justify-content-center mt-2 mb-4">
<div class="col-sm-8 col-md-9">    
<span class="text-dark h5"><b>Treasurer</b></span><br>
    <table class="table table-borderless text-dark">
        <thead>
            <tr>
                <th width="10%" class="text-center">Rank</th>
                <th>Candidate</th>
                <th width="10%" class="text-center">Votes</th>
                <th width="10%"class="text-center">Percent</th>
            </tr>
        </thead>
    <tbody class="bg-white text-uppercase" style="font-size:1em;font-weight:900;">

        @foreach($treasurer_results as $candidate)
            <tr>
                <td class="text-center">{{$candidate->rank}}</td>
                <td><img src="{{url ($candidate->image)}}" height="25px" class="rounded"> {{$candidate->firstname}} {{$candidate->lastname}}</td>
                <td class="text-center">{{$candidate->occurrences}}</td>
                <td class="text-center">{{$candidate->percent}}%</td>
            </tr>
        @endforeach   
    </tbody>
    </table>
</div>
</div>
<!-- END -->





@stop