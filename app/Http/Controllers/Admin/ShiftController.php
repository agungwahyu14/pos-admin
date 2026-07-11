<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index(Request $request)
    {
        $query = Shift::with(['user']);
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }
        
        $shifts = $query->latest()->paginate(10);
        return view('admin.shifts.index', compact('shifts'));
    }

    public function show(Shift $shift)
    {
        $shift->load(['user', 'orders']);
        return view('admin.shifts.show', compact('shift'));
    }
}
