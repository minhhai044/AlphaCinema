<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cinema;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Branch;

class CinemaController extends Controller
{
    private const PATH_VIEW = 'admin.cinemas.';
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cinemas = Cinema::with('branch')->orderByDesc('id')->get();

        return view(self::PATH_VIEW . __FUNCTION__, compact('cinemas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branchs = Branch::query()->orderByDesc('id')->get();
        return view(self::PATH_VIEW . __FUNCTION__, compact('branchs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        try {
            $data['is_active'] ??= 0;

            if (!empty($data['name'])) {
                $data['slug'] = Str::slug($data['name'], '-') . '-' . Str::ulid();
            }

            Cinema::create($data);

            return redirect()->route('admin.cinemas.index');
        } catch (\Throwable $th) {
            die('Error' . $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cinema $cinema)
    {
        $cinema->load('branch');

        return view(self::PATH_VIEW . __FUNCTION__, compact('cinema'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cinema $cinema)
    {
        return view(self::PATH_VIEW . __FUNCTION__);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cinema $cinema)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cinema $cinema)
    {
        //
    }
}
