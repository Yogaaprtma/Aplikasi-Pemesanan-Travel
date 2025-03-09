<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TravelSchedule;

class PenumpangController extends Controller
{
    public function index()
    {
        $travelSchedules = TravelSchedule::all();
        return view('penumpang.dashboard', compact('travelSchedules'));
    }
}