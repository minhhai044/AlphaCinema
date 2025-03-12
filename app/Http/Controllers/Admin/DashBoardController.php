<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashBoardController extends Controller
{
    private const PATH_VIEW = 'admin.';
    public function index()
    {
        return view(self::PATH_VIEW . __FUNCTION__);
    }
}
