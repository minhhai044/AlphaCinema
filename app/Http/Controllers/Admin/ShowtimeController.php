<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ShowtimeService;
use Illuminate\Http\Request;

class ShowtimeController extends Controller
{
    private const PATH_VIEW = "admin.showtimes.";

    private $showtimeService;
    public function __construct(ShowtimeService $showtimeService)
    {
        $this->showtimeService = $showtimeService;
    }
    public function index()
    {

        return view(self::PATH_VIEW . __FUNCTION__);
    }
    public function create()
    {

        [$branchs, $branchsRelation, $rooms, $movies,$days,$slug,$versions] = $this->showtimeService->createService();
        // dd($branchs, $branchsRelation, $rooms, $movies,$days);
        return view(self::PATH_VIEW . __FUNCTION__,compact('branchs','branchsRelation','rooms','movies','days','slug','versions'));
    }
}
