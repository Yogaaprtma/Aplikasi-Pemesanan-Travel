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
            'origin'         => 'required|string|max:255',
            'destination'    => 'required|string|max:255',
            'departure_time' => 'required|date|after:now',
            'quota'          => 'required|integer|min:1|max:100',
            'price'          => 'required|numeric|min:0',
            'vehicle_type'   => 'required|in:bus,minivan,car',
            'description'    => 'nullable|string|max:500',
        ], [
            'origin.required'         => 'Kota asal harus diisi.',
            'destination.required'    => 'Kota tujuan harus diisi.',
            'departure_time.after'    => 'Waktu keberangkatan harus di masa mendatang.',
            'quota.min'               => 'Kuota minimal 1 kursi.',
            'quota.max'               => 'Kuota maksimal 100 kursi.',
            'vehicle_type.required'   => 'Jenis kendaraan harus dipilih.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('jadwal.create')
                ->withErrors($validator)
                ->withInput();
        }

        TravelSchedule::create([
            'origin'         => $request->origin,
            'destination'    => $request->destination,
            'departure_time' => $request->departure_time,
            'quota'          => $request->quota,
            'price'          => $request->price,
            'vehicle_type'   => $request->vehicle_type,
            'description'    => $request->description,
        ]);

        return redirect()->route('jadwal.page')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function editJadwal($id)
    {
        $schedule = TravelSchedule::findOrFail($id);
        return view('admin.travel.edit', compact('schedule'));
    }

    public function updateJadwal(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'origin'         => 'required|string|max:255',
            'destination'    => 'required|string|max:255',
            'departure_time' => 'required|date',
            'quota'          => 'required|integer|min:0|max:100',
            'price'          => 'required|numeric|min:0',
            'vehicle_type'   => 'required|in:bus,minivan,car',
            'description'    => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->route('jadwal.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        $schedule = TravelSchedule::findOrFail($id);
        $schedule->update([
            'origin'         => $request->origin,
            'destination'    => $request->destination,
            'departure_time' => $request->departure_time,
            'quota'          => $request->quota,
            'price'          => $request->price,
            'vehicle_type'   => $request->vehicle_type,
            'description'    => $request->description,
        ]);

        return redirect()->route('jadwal.page')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroyJadwal($id)
    {
        $schedule = TravelSchedule::findOrFail($id);

        // Cek apakah ada booking yang masih aktif
        $activeBookings = $schedule->bookings()->whereIn('status', ['pending', 'confirmed'])->count();
        if ($activeBookings > 0) {
            return redirect()->route('jadwal.page')
                ->with('error', 'Jadwal tidak bisa dihapus karena masih ada ' . $activeBookings . ' booking aktif.');
        }

        $schedule->delete();

        return redirect()->route('jadwal.page')->with('success', 'Jadwal berhasil dihapus.');
    }
}