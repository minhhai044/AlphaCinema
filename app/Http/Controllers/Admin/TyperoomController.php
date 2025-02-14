<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TypeRoomRequest;
use App\Models\Type_room;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Services\TypeRoomService;
use Illuminate\Http\Request;

class TyperoomController extends Controller
{
    private const PATH_VIEW = 'admin.typerooms.';
    /**
     * Display a listing of the resource.
     */
    private $typeRoomService;
    public function __construct(TypeRoomService $typeRoomService)
    {
        $this->typeRoomService = $typeRoomService;
    }
    public function index()
    {
        $type_rooms = Type_room::with('branch')->orderByDesc('id')->get();

        return view(self::PATH_VIEW . __FUNCTION__, compact('type_rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(TypeRoomRequest $request)
    {
        $data = $request->validated();
        try {
            $this->typeRoomService->storeService($data);
            return redirect()->route('admin.typerooms.index')
                ->with('success', 'THAO TÁC THÀNH CÔNG');
                
        } catch (\Throwable $th) {
            back()
                ->with('error', 'THAO TÁC KHÔNG THÀNH CÔNG');
        }
    }

    /**
     * Display the specified resource.
     */
    // public function show(Type_room $type_room)
    // {
    //     // $type_room->load('branch');

    //     // return view(self::PATH_VIEW . __FUNCTION__, compact('type_room'));
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Type_room $type_room) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(TypeRoomRequest $typeRoomRequest, string $id)
    {
        try {
            $data = $typeRoomRequest->validated();
            $this->typeRoomService->updateService($data, $id);
            return redirect()->route('admin.typerooms.index')
                ->with('success', 'THAO TÁC THÀNH CÔNG');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('error', 'THAO TÁC KHÔNG THÀNH CÔNG');
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Type_room $type_room)
    {
        // dd ($typeRoomRequest);
        try {
            $type_room->delete(); // Sử dụng tên biến mới.

            return back()
                ->with('success', 'Xóa thành công');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
        //
    }
}
