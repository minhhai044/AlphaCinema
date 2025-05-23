<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DayRequest;
use App\Models\Day;
use App\Services\DayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DayController extends Controller
{
    protected $dayService;

    public function __construct(DayService $dayService)
    {
        $this->dayService = $dayService;
        $this->middleware('can:Danh sách ngày')->only('index');
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

        $request->validate([
            'day_surcharge' => 'required|integer|min:0',
        ]);

        $day->day_surcharge = $request->day_surcharge;
        $day->save();

        return response()->json([
            'success' => true,
            'data' => $day
        ]);
    }




    public function destroy($id)
    {
        $this->dayService->deleteDay($id);
        return redirect()->route('admin.days.index')->with('success', 'Xóa thành công!');
    }
}
