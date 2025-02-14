<?php

use Illuminate\Support\Str;
## * Viết các function helper cho ứng dụng * ##

/**
 * Check trường là active hay no active
 * 
 * @param boolean $data
 * 
 * @return string
 */
if (!function_exists('matchActive')) {
    function matchActive($data)
    {
        return $data ? 'Active' : 'No Active';
    }
}
/**
 * Match class dựa theo 0/1 của data
 * 
 * @param boolean $data
 * 
 * @return string
 */
if (!function_exists('matchActiveClass')) {
    function matchActiveClass($data)
    {
        return $data ? 'badge-soft-success' : 'badge-soft-danger';
    }
}
/**
 * Format giá tiền từ 100000 => 100.000
 * 
 * @param int|string $price
 * 
 * @return int|string
 */
if (!function_exists('formatPrice')) {
    function formatPrice($price)
    {
        return number_format($price);
    }
}
/**
 * Giới hạn hiển thị text
 * 
 * @param string $text  Đoạn text muốn giới hạn
 * @param int    $limit Giới hạn từ muốn hiển thị
 * @param string $end   Chuỗi string sẽ nối vào cuối cùng của chuỗi sau khi giới hạn
 * 
 * @return string Trả về string sau khi đã giới hạn
 */
if (!function_exists('limitText')) {
    function limitText($text, $limit, $end = "...")
    {
        return Str::length($text) > $limit ? Str::limit($text, $limit, $end) : $text;
    }
}
