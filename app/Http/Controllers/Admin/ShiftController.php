<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index(Request $request)
    {
        $shifts = Shift::with(['user'])->latest()->get();
        return view('admin.shifts.index', compact('shifts'));
    }

    public function show(Shift $shift)
    {
        $shift->load(['user', 'orders']);
        return view('admin.shifts.show', compact('shift'));
    }
}
