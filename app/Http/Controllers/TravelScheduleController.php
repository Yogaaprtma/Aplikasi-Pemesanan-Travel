<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TravelSchedule;
use Illuminate\Support\Facades\Validator;

class TravelScheduleController extends Controller
{
    public function createJadwal() 
    {
        return view('admin.travel.create');
    }

    public function storeJadwal(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'destination' => 'required|string',
            'departure_time' => 'required|date',
            'quota' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->route('jadwal.create')
                ->withErrors($validator)
                ->withInput();
        }

        TravelSchedule::create([
            'destination' => $request->destination,
            'departure_time' => $request->departure_time,
            'quota' => $request->quota,
            'price' => $request->price
        ]);
        
        return redirect()->route('jadwal.page')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function editJadwal($id)
    {
        $schedule = TravelSchedule::find($id);
        return view('admin.travel.edit', compact('schedule'));
    }

    public function updateJadwal(Request $request, $id) 
    {
        $validator = Validator::make($request->all(), [
            'destination' => 'required|string',
            'departure_time' => 'required|date',
            'quota' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->route('jadwal.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        $schedule = TravelSchedule::findOrFail($id);

        $schedule->update([
            'destination' => $request->destination,
            'departure_time' => $request->departure_time,
            'quota' => $request->quota,
            'price' => $request->price
        ]);

        return redirect()->route('jadwal.page')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroyJadwal($id) 
    {
        $schedule = TravelSchedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('jadwal.page')->with('success', 'Jadwal berhasil dihapus.');
    }
}