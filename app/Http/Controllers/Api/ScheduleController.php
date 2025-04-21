<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HelpdeskSchedule;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = HelpdeskSchedule::with('user')->orderBy('date')->get();
        return response()->json($schedules);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'shift_pagi_user_id' => 'nullable|string',
            'shift_sore_user_id' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $schedule = HelpdeskSchedule::create($validated);
        return response()->json($schedule);
    }

    public function update(Request $request, $id)
    {
        $schedule = HelpdeskSchedule::findOrFail($id);

        $validated = $request->validate([
            'date' => 'required|date',
            'shift_pagi_user_id' => 'nullable|string',
            'shift_sore_user_id' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $schedule->update($validated);
        return response()->json($schedule);
    }
}
