<?php

namespace App\Http\Controllers;

use App\Models\SpecialAttendance;
use Illuminate\Http\Request;

class SpecialAttendanceController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'isAdmin']); 
    }

    public function index()
    {
        return view('admin.specialattendance.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SpecialAttendance  $specialAttendance
     * @return \Illuminate\Http\Response
     */
    public function show(SpecialAttendance $specialAttendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SpecialAttendance  $specialAttendance
     * @return \Illuminate\Http\Response
     */
    public function edit(SpecialAttendance $specialAttendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SpecialAttendance  $specialAttendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SpecialAttendance $specialAttendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SpecialAttendance  $specialAttendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(SpecialAttendance $specialAttendance)
    {
        //
    }
}
