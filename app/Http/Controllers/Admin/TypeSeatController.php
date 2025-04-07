<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TypeSeatRequest;
use App\Models\Type_seat;
use App\Services\TypeSeatService;
use Illuminate\Http\Request;

class TypeSeatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    const PATH_VIEW = 'admin.type_seats.'; 
    private $typeSeatService;
    public function __construct(TypeSeatService $typeSeatService)
    {
        $this->typeSeatService = $typeSeatService;
    }
    public function index()
    {
        $typeSeats = Type_seat::query()->latest('id')->get(); // Đổi tên biến cho dễ hiểu.
        return view(self::PATH_VIEW . __FUNCTION__, compact('typeSeats')); // Sử dụng tên biến mới.
    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view(self::PATH_VIEW . __FUNCTION__);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TypeSeatRequest $request )
    {
        // $data = $request->validated();
        // try {
        //     $this->typeSeatService->storeService($data);
        //     return redirect()->route('admin.type_seats.index')
        //         ->with('THAO TÁC KHÔNG THÀNH CÔNG');
        // } catch (\Throwable $th) {
        //     return back()
        //         ->with('THAO TÁC KHÔNG THÀNH CÔNG');
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Type_seat $type_seat)
    {
        return view(self::PATH_VIEW . __FUNCTION__,compact('type_seat'));
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TypeSeatRequest $typeSeatRequest, string $id)
    {
        try {
            $data = $typeSeatRequest->validated();
            $this->typeSeatService->updateService(['price' => $data['price']],$id);
            return redirect()->route('admin.type_seats.index')
                ->with('success','THAO TÁC THÀNH CÔNG');
        } catch (\Throwable $th) {
            return back()
            ->with('THAO TÁC KHÔNG THÀNH CÔNG');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
