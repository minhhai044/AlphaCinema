<?php

namespace App\Http\Requests;

use App\Models\Branch;
use App\Models\Cinema;
use App\Models\Room;
use App\Models\Seat_template;
use App\Models\Type_room;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoomChatRequest extends FormRequest
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

        // post => rulesForCreate 
        // put/patch => rulesForUpdate

        if ($this->isMethod('post')) {
            return $this->rulesForCreate();
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            return $this->rulesForUpdate();
        }

        return [];
    }
    public function rulesForCreate()
    {
        return [
            'messenge' => 'nullable|string',
            'image' => 'nullable|file|mimetypes:image/jpeg,image/png,image/jpg,video/mp4,video/quicktime|max:20480',
            'user_id' => 'required|exists:users,id',
            'room_chat_id' => 'required|exists:room_chats,id',
        ];
    }

    public function rulesForUpdate()
    {
        return [];
    }

    // messages chung
    public function messages()
    {
        return [
            'messenge.string' => 'Tin nhắn phải là chuỗi văn bản.',
            'image.file' => 'Tệp tải lên phải là một tệp hợp lệ.',
            'image.mimetypes' => 'Tệp phải là hình ảnh (jpg, jpeg, png) hoặc video (mp4, mov).',
            'image.max' => 'Dung lượng tệp không được vượt quá 20MB.',
            'user_id.required' => 'Người dùng là bắt buộc.',
            'user_id.exists' => 'Người dùng không tồn tại.',
            'room_chat_id.required' => 'Phòng chat là bắt buộc.',
            'room_chat_id.exists' => 'Phòng chat không tồn tại.',
        ];
    }
}
