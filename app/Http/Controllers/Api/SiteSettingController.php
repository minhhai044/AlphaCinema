<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest;
use App\Models\Site_setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        // 🔥 Lấy bản ghi đầu tiên trong database
        $settings = Site_setting::first();

        // 🛠️ Nếu chưa có dữ liệu, trả về giá trị mặc định (KHÔNG lưu vào DB)
        if (!$settings) {
            $settings = new Site_setting(Site_setting::defaultSetting());
        }

        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SettingRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SettingRequest $request, string $id)
    {
        //
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
   
}
