<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SeatTemplateService;

class SeatTemplateControler extends Controller
{
    private const PATH_VIEW = 'admin.seat_templates.';
    private $seatTemplateService;
    public function __construct(SeatTemplateService $seatTemplateService)
    {
        $this->seatTemplateService = $seatTemplateService;
    }
    public function index(){
        $dataAll = $this->seatTemplateService->getAll();
        return view(self::PATH_VIEW.__FUNCTION__ , compact('dataAll'));
    }
    
}
