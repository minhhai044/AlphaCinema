<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // if ($this->isMethod('post')) {
        //     return $this->rulesForCreate();
        // } else
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            return $this->rulesForUpdate();
        }

        return [];
    }

    /**
     * Validation rules for creating a branch.
     */
    // public function rulesForCreate(): array
    // {
    //     return [
    //         'name' => 'required|string|max:255|unique:branches,name',
    //     ];
    // }

    /**
     * Validation rules for updating a branch.
     */
    public function rulesForUpdate(): array
    {
        return [
            'website_logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'site_name' => 'required|string|max:255',
            'brand_name' => 'required|string|max:255',
            'slogan' => 'required|string|max:255',
            'phone' => 'required|string|max:20|regex:/^[0-9+()\-\s]*$/', 
            'email' => 'required|email|max:255',
            'headquarters' => 'required|string|max:500',
            'business_license' => 'required|string|max:255',
            'working_hours' => 'required|string|max:255',
            'facebook_link' => 'nullable|url',
            'youtube_link' => 'nullable|url',
            'instagram_link' => 'nullable|url',
            'privacy_policy_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'privacy_policy' => 'required|string',
            'terms_of_service_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'terms_of_service' => 'required|string',
            'introduction_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'introduction' => 'required|string',
            'copyright' => 'required|string|max:500',
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'website_logo.image' => 'Logo website phải là một ảnh.',
            'website_logo.mimes' => 'Logo website phải có định dạng jpeg, png, jpg, gif, hoặc svg.',
            'website_logo.max' => 'Logo website không được vượt quá 2MB.',

            'site_name.required' => 'Tên website là bắt buộc.',
            'site_name.string' => 'Tên website phải là chuỗi ký tự.',
            'site_name.max' => 'Tên website không được vượt quá 255 ký tự.',

            'brand_name.required' => 'Tên thương hiệu là bắt buộc.',
            'brand_name.string' => 'Tên thương hiệu phải là chuỗi ký tự.',
            'brand_name.max' => 'Tên thương hiệu không được vượt quá 255 ký tự.',

            'slogan.required' => 'Khẩu hiệu là bắt buộc.',
            'slogan.string' => 'Khẩu hiệu phải là chuỗi ký tự.',
            'slogan.max' => 'Khẩu hiệu không được vượt quá 255 ký tự.',

            'phone.required' => 'Số điện thoại là bắt buộc.',
            'phone.regex' => 'Số điện thoại không đúng định dạng. Sử dụng định dạng +84 hoặc 0.',

            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',

            'headquarters.required' => 'Trụ sở chính là bắt buộc.',
            'headquarters.string' => 'Trụ sở chính phải là chuỗi ký tự.',
            'headquarters.max' => 'Trụ sở chính không được vượt quá 500 ký tự.',

            'business_license.required' => 'Giấy phép kinh doanh là bắt buộc.',
            'business_license.string' => 'Giấy phép kinh doanh phải là chuỗi ký tự.',
            'business_license.max' => 'Giấy phép kinh doanh không được vượt quá 255 ký tự.',

            'working_hours.required' => 'Thời gian làm việc là bắt buộc.',
            'working_hours.string' => 'Thời gian làm việc phải là chuỗi ký tự.',
            'working_hours.max' => 'Thời gian làm việc không được vượt quá 255 ký tự.',

            'facebook_link.url' => 'Link Facebook không đúng định dạng URL.',
            'youtube_link.url' => 'Link Youtube không đúng định dạng URL.',
            'instagram_link.url' => 'Link Instagram không đúng định dạng URL.',

            // 'privacy_policy_image.required' => 'Ảnh đại diện cho chính sách bảo mật là bắt buộc.',
            'privacy_policy_image.image' => 'Ảnh đại diện cho chính sách bảo mật phải là một ảnh.',
            'privacy_policy_image.mimes' => 'Ảnh đại diện cho chính sách bảo mật phải có định dạng jpeg, png, jpg, gif, hoặc svg.',
            'privacy_policy_image.max' => 'Ảnh đại diện cho chính sách bảo mật không được vượt quá 2MB.',

            'privacy_policy.required' => 'Nội dung chính sách bảo mật là bắt buộc.',

            // 'terms_of_service_image.required' => 'Ảnh đại diện cho điều khoản dịch vụ là bắt buộc.',
            'terms_of_service_image.image' => 'Ảnh đại diện cho điều khoản dịch vụ phải là một ảnh.',
            'terms_of_service_image.mimes' => 'Ảnh đại diện cho điều khoản dịch vụ phải có định dạng jpeg, png, jpg, gif, hoặc svg.',
            'terms_of_service_image.max' => 'Ảnh đại diện cho điều khoản dịch vụ không được vượt quá 2MB.',

            'terms_of_service.required' => 'Nội dung điều khoản dịch vụ là bắt buộc.',

            // 'introduction_image.required' => 'Ảnh đại diện cho giới thiệu là bắt buộc.',
            'introduction_image.image' => 'Ảnh đại diện cho giới thiệu phải là một ảnh.',
            'introduction_image.mimes' => 'Ảnh đại diện cho giới thiệu phải có định dạng jpeg, png, jpg, gif, hoặc svg.',
            'introduction_image.max' => 'Ảnh đại diện cho giới thiệu không được vượt quá 2MB.',

            'introduction.required' => 'Nội dung giới thiệu là bắt buộc.',

            'copyright.required' => 'Bản quyền là bắt buộc.',
            'copyright.string' => 'Bản quyền phải là chuỗi ký tự.',
            'copyright.max' => 'Bản quyền không được vượt quá 500 ký tự.',
        ];
    }
}
