<?php

namespace App\Services;

use App\Models\Site_setting;
use Illuminate\Support\Facades\Storage;

class SiteSettingService
{
    public function storeService(array $data)
    {
        return Site_setting::create($data);
    }
    public function updateService(array $data,string $id)
    {
        $Site_setting=Site_setting::findOrFail($id);
        // Danh sách các trường ảnh cần xử lý
        $imageFields = ['website_logo', 'privacy_policy_image', 'terms_of_service_image', 'introduction_image'];

        foreach ($imageFields as $field) {
            if (isset($data[$field]) && $data[$field] instanceof \Illuminate\Http\UploadedFile) {
                // Lưu ảnh mới vào storage/app/public/settings/
                $imagePath = $data[$field]->store('settings', 'public');
    
                // Xóa ảnh cũ trong storage nếu có (tránh file rác)
                if ($Site_setting->$field && Storage::disk('public')->exists($Site_setting->$field)) {
                    Storage::disk('public')->delete($Site_setting->$field);
                }
    
                // Lưu đường dẫn ảnh mới vào DB (chỉ lưu 'settings/...')
                $data[$field] = $imagePath;
            }
        }
        $Site_setting->update($data);
        return $Site_setting;
    }
    
    
}
