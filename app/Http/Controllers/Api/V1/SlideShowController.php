<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Slideshow;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Storage;

class SlideShowController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $slideshows = Slideshow::latest('id')->get()->flatMap(function ($slide) {
            $thumbnails = json_decode($slide->img_thumbnail, true) ?? [];

            return array_map(function ($path) {
                return Storage::exists($path) ? Storage::url($path) : null;
            }, $thumbnails);
        })->filter()->values();

        return $this->successResponse($slideshows, 'Danh sách slideshows');
    }
}
