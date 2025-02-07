<?php

namespace App\Helpers;

class Toastr
{
    /**
     * Hiển thị Toastr 
     * 
     * @return mixed Trả về một instance Toastr
     */
    public static function showToastr()
    {
        return toastr()
            ->closeButton()
            ->preventDuplicates(true)
            ->newestOnTop(true);
    }
    /**
     * Hiển thị thông báo thành công
     * 
     * @param string|null $message  Nội dung của thông báo
     * @param string|null $title    Tiêu đề của thông báo 
     * 
     * @return mixed Trả về instance Toastr thông báo thành công
     */
    public static function success($message = null, $title = '')
    {
        return self::showToastr()->addSuccess($message, $title ?: '');
    }
    /**
     * Hiển thị thông báo lỗi
     * 
     * @param string|null $message  Nội dung của thông báo
     * @param string|null $title    Tiêu đề của thông báo 
     * 
     * @return mixed Trả về instance Toastr thông báo lỗi
     */
    public static function error($message = null, $title = null)
    {
        return self::showToastr()->addError($message, $title);
    }
    /**
     * Hiển thị thông báo cảnh báo
     * 
     * @param string|null $message  Nội dung của thông báo
     * @param string|null $title    Tiêu đề của thông báo 
     * 
     * @return mixed Trả về instance Toastr thông báo cảnh báo
     */
    public static function warning($message = null, $title = null)
    {
        return self::showToastr()->addWarning($message, $title);
    }
    /**
     * Hiển thị thông báo thông tin
     * 
     * @param string|null $message  Nội dung của thông báo
     * @param string|null $title    Tiêu đề của thông báo 
     * 
     * @return mixed Trả về instance Toastr thông báo thông tin
     */
    public static function info($message = null, $title = null)
    {
        return self::showToastr()->addInfo($message, $title);
    }
}
