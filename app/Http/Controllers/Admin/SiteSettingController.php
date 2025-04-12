<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest;
use App\Models\Site_setting;
use App\Services\SiteSettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class SiteSettingController extends Controller
{

    private const PATH_VIEW = 'admin.site_setting.';
    private $settingService;
    public function __construct(SiteSettingService $settingService)
    {
        $this->settingService = $settingService;
        $this->middleware('can:Cấu hình website')->only('index', 'update', 'resetToDefault');
    }

    public function index()
    {

        // $settings = Site_setting::firstOrCreate([], Site_setting::defaultSetting());
        // $settings->fill(Site_setting::defaultSetting())->save();


        $settings = Site_setting::first();

        // 🔥 Nếu chưa có bản ghi, tạo một đối tượng mới với giá trị mặc định (không lưu vào DB)
        if (!$settings) {
            // $settings = new Site_setting(Site_setting::defaultSetting());
            $settings = Site_setting::firstOrCreate([], Site_setting::defaultSetting());
        $settings->fill(Site_setting::defaultSetting())->save();

        }
        return view(self::PATH_VIEW . __FUNCTION__, compact('settings'));
    }

    public function update(SettingRequest $settingRequest, string $id)
    {
        //
        try {
            $data = $settingRequest->validated();
            $this->settingService->updateService($data, $id);
            // dd($data);
            return redirect()->route('admin.settings.index')->with('success', 'Cập nhật thành công!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại.');
        }
    }
    public function resetToDefault()
    {



        $settings = Site_setting::firstOrCreate([], Site_setting::defaultSetting());

        // Đảm bảo tất cả các cột có giá trị từ defaultSetting()
        $settings->fill(Site_setting::defaultSetting())->save();

        $settings = Site_setting::first();

        // Danh sách các trường ảnh cần kiểm tra và xóa
        $images = [
            'website_logo',
            'introduction_image',
            'terms_of_service_image',
            'privacy_policy_image'
        ];

        // Kiểm tra và xóa từng file nếu có
        foreach ($images as $imageField) {
            if ($settings->$imageField && Storage::exists($settings->$imageField)) {
                Storage::delete($settings->$imageField);
            }
        }

        // Đặt lại cài đặt về giá trị mặc định
        $settings->defaultSetting();

        return redirect()->back()->with('success', 'Đã đặt lại cài đặt về giá trị mặc định!');
    }
}
