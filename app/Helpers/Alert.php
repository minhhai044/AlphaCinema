<?php

namespace App\Helpers;

class Alert
{
    /**
     * Hiển thị SweetAlert 
     * 
     * @return mixed Trả về một instance SweetAlert
     */
    public static function showAlert()
    {
        return sweetalert()
            ->timer(3000)
            ->showCloseButton();
    }
    /**
     * Hiển thị thông báo thành công
     * 
     * @param string|null $message  Nội dung của thông báo
     * @param string|null $title    Tiêu đề của thông báo 
     * 
     * @return mixed Trả về instance SweetAlert thông báo thành công
     */
    public static function success($message = null, $title = null)
    {
        return self::showAlert()->addSuccess($message, $title);
    }
    /**
     * Hiển thị thông báo lỗi
     * 
     * @param string|null $message  Nội dung của thông báo
     * @param string|null $title    Tiêu đề của thông báo 
     * 
     * @return mixed Trả về instance SweetAlert thông báo lỗi
     */
    public static function error($message = null, $title = null)
    {
        return self::showAlert()->addError($message, $title);
    }
    /**
     * Hiển thị thông báo cảnh báo
     * 
     * @param string|null $message  Nội dung của thông báo
     * @param string|null $title    Tiêu đề của thông báo 
     * 
     * @return mixed Trả về instance SweetAlert thông báo cảnh báo
     */
    public static function warning($message = null, $title = null)
    {
        return self::showAlert()->addWarning($message, $title);
    }
    /**
     * Hiển thị thông báo thông tin
     * 
     * @param string|null $message  Nội dung của thông báo
     * @param string|null $title    Tiêu đề của thông báo 
     * 
     * @return mixed Trả về instance SweetAlert thông báo thông tin
     */
    public static function info($message = null, $title = null)
    {
        return self::showAlert()->addInfo($message, $title);
    }
}
