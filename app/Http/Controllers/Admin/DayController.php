<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DayRequest;
use App\Models\Day;
use App\Services\DayService;
use Illuminate\Http\Request;

class DayController extends Controller
{
    protected $dayService;

    public function __construct(DayService $dayService)
    {
        $this->dayService = $dayService;
    }

    public function index()
    {
        $days = $this->dayService->getAllDays();
        return view('admin.days.index', compact('days'));
    }

    public function create()
    {
        return view('admin.days.create');
    }

    public function store(DayRequest $request)
    {
        $validated = $request->validated();
        $day = $this->dayService->createDay($validated); 
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'day' => $day,
            ]);
        }

        return redirect()->route('admin.days.index')->with('success', 'Thêm phim thành công!');
    }

    public function show($id)
    {
        $days = $this->dayService->getDayById($id);
        return view('admin.days.show', compact('days'));
    }



    public function update(Request $request, $id)
    {
        $day = Day::findOrFail($id);
        $day->name = $request->name;
        $day->day_surcharge = $request->day_surcharge;
        $day->save();

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $this->dayService->deleteDay($id);
        return redirect()->route('admin.days.index')->with('success', 'Xóa thành công!');
    }
}
