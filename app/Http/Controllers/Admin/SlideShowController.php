<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Toastr;
use App\Http\Controllers\Controller;
use App\Http\Requests\SlideShowRequest;
use App\Models\Slideshow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SlideShowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private const PATH_VIEW = 'admin.slideshows.';
    private const PATH_UPLOAD = 'slideshows';

    public function index()
    {
        $slideshows = Slideshow::latest()->get();

        // Chuyển img_thumbnail từ JSON về mảng
        foreach ($slideshows as $slideshow) {
            $slideshow->img_thumbnail = json_decode($slideshow->img_thumbnail, true);
        }
        return view(self::PATH_VIEW . __FUNCTION__, compact('slideshows'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view(self::PATH_VIEW . __FUNCTION__);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SlideShowRequest $SlideShowRequest)
    {
        $data = $SlideShowRequest->validated();

        $imagePaths = [];

        try {
            DB::beginTransaction();

            $data['is_active'] = 1;

            // Xử lý ảnh
            if ($SlideShowRequest->hasFile('img_thumbnail')) {
                foreach ($SlideShowRequest->file('img_thumbnail') as $file) {
                    $path = $file->store(self::PATH_UPLOAD);
                    $imagePaths[] = $path;
                }
            }

            $data['img_thumbnail'] = json_encode($imagePaths);

            // Thêm vào database
            Slideshow::create($data);

            // Commit transaction nếu thành công
            DB::commit();

            Toastr::success('Thêm mới thành công', 'AlphaCinema thông báo');
            return redirect()->route('admin.slideshows.index');
        } catch (\Throwable $th) {
            DB::rollBack();

            // Nếu lỗi, xóa ảnh đã lưu trong storage
            foreach ($imagePaths as $path) {
                if (Storage::exists($path)) {
                    Storage::delete($path);
                }
            }

            Log::error(__CLASS__ . __FUNCTION__, [$th->getMessage()]);

            return back()->with('error', 'Thêm mới không thành công !!!');
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $slide = Slideshow::findOrFail($id);
        $slide->img_thumbnail = json_decode($slide->img_thumbnail, true) ?? []; // Giải mã JSON

        return view(self::PATH_VIEW . __FUNCTION__, compact('slide'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(SlideShowRequest $request, string $id)
    {
        try {
            $slide = Slideshow::findOrFail($id);
            $data = $request->all();

            $data['is_active'] = $request->has('is_active') ? 1 : 0;
            $existingImages = $request->input('existing_images', []);
            $updatedImages = $existingImages;

            if ($request->hasFile('img_thumbnail')) {
                foreach ($request->file('img_thumbnail') as $key => $file) {
                    if (isset($existingImages[$key])) {
                        $oldPath = $existingImages[$key];
                        if (Storage::exists($oldPath)) {
                            Storage::delete($oldPath);
                        }
                    }
                    $updatedImages[$key] = $file->store('uploads/slides');
                }
            }

            $slide->update([
                'img_thumbnail' => json_encode(array_values($updatedImages)), // Lưu dưới dạng JSON
                'description' => $data['description'],
                'is_active' => $data['is_active'],
            ]);

            Toastr::success('null', 'Cập nhật thành công');
            return redirect()
                ->route('admin.slideshows.index');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }






    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $slide = Slideshow::findOrFail($id);
        $imagePaths = json_decode($slide->img_thumbnail, true);
        if (!empty($imagePaths)) {
            foreach ($imagePaths as $path) {
                if (Storage::exists($path)) {
                    Storage::delete($path);
                }
            }
        }

        $slide->delete();
        Toastr::success('Xóa thành công', 'AlphaCinema thông báo');
        return redirect()
            ->route('admin.slideshows.index');
    }
}
