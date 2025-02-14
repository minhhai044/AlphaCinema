<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class DynamicExport implements FromCollection, WithHeadings, WithColumnWidths, WithCustomValueBinder
{
    /**
     * 
     * @var Model $model; 
     */
    protected $model;
    protected $columns;
    protected $headings;
    protected $imageColumns;
    protected $relations;

    public function __construct($model, $columns, $headings, $relations = [], $imageColumns = [])
    {
        $this->model = is_string($model) ? new $model() : $model;
        $this->columns = $columns;
        $this->headings = $headings;
        $this->imageColumns = $imageColumns;
        $this->relations = $relations;
    }

    public function collection()
    {
        // Khởi tạo query builder
        $query = $this->model->query();

        // Xử lý các trường có relationship
        foreach ($this->columns as $column) {
            if (strpos($column, '.') !== false) {
                // Tách tên bảng và cột từ chuỗi "branch.name"
                [$relation, $relatedColumn] = explode('.', $column);

                // Lấy model liên quan từ relationship
                $relatedModel = $this->model->$relation()->getRelated();
                $relatedTable = $relatedModel->getTable(); // Lấy tên bảng từ model liên quan

                // Thêm join để lấy dữ liệu từ bảng liên quan
                $query->leftJoin(
                    $relatedTable,
                    "{$relatedTable}.id",
                    '=',
                    "{$this->model->getTable()}.{$relation}_id"
                )->addSelect("{$relatedTable}.{$relatedColumn} as {$relation}_{$relatedColumn}");
            } else {
                // Thêm cột bình thường
                $query->addSelect("{$this->model->getTable()}.$column");
            }
        }

        // Sắp xếp theo id giảm dần
        $query->orderByDesc("{$this->model->getTable()}.id");

        return $query->get();
    }

    public function headings(): array
    {
        return $this->headings;
    }

    // public function drawings()
    // {
    //     $drawings = [];

    //     // Lấy dữ liệu từ model
    //     $data = $this->model::select($this->columns)->get();

    //     foreach ($data as $index => $row) {
    //         foreach ($this->imageColumns as $imageColumn) {
    //             if (!empty($row->$imageColumn)) {
    //                 $drawing = new Drawing();
    //                 $drawing->setName('Image');
    //                 $drawing->setDescription('Image from column ' . $imageColumn);
    //                 $drawing->setPath(Storage::url($row->$imageColumn)); // Đường dẫn đến ảnh
    //                 $drawing->setHeight(50); // Chiều cao ảnh
    //                 $drawing->setWidth(50);  // Chiều rộng ảnh

    //                 // Xác định vị trí ô để chèn ảnh
    //                 $columnIndex = array_search($imageColumn, $this->columns) + 1; // Tìm vị trí cột
    //                 $columnLetter = chr(64 + $columnIndex); // Chuyển số cột thành chữ (A, B, C...)
    //                 $rowNumber = $index + 2; // Hàng bắt đầu từ 2 (hàng đầu tiên là tiêu đề)

    //                 $drawing->setCoordinates("{$columnLetter}{$rowNumber}");

    //                 $drawings[] = $drawing;
    //             }
    //         }
    //     }

    //     return $drawings;
    // }


    // public function drawings()
    // {
    //     $drawings = [];

    //     // Lấy dữ liệu từ model
    //     // $data = $this->model::select($this->columns)->get();
    //     $data = $this->collection();

    //     foreach ($data as $index => $row) {
    //         foreach ($this->imageColumns as $imageColumn) {
    //             if (!empty($row->$imageColumn)) {
    //                 $drawing = new Drawing();
    //                 $drawing->setName('Image');
    //                 $drawing->setDescription('Image from column ' . $imageColumn);

    //                 // Lấy đường dẫn thực của file
    //                 // $imagePath = storage_path('app/public/' . $row->$imageColumn);
    //                 $imagePath = Storage::url($row->$imageColumn);

    //                 // Kiểm tra file tồn tại
    //                 if (file_exists($imagePath)) {
    //                     $drawing->setPath($imagePath);

    //                     // Thiết lập kích thước ảnh
    //                     $drawing->setHeight(80);
    //                     $drawing->setWidth(80);

    //                     // Thiết lập vị trí ảnh
    //                     $columnIndex = array_search($imageColumn, $this->columns);
    //                     $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex + 1);
    //                     $rowNumber = $index + 2;

    //                     $drawing->setCoordinates($columnLetter . $rowNumber);

    //                     // Thiết lập căn chỉnh
    //                     $drawing->setOffsetX(5); // Căn lề trái 5px
    //                     $drawing->setOffsetY(5); // Căn lề trên 5px

    //                     // Thiết lập rotation (0 = không xoay)
    //                     $drawing->setRotation(0);

    //                     $drawings[] = $drawing;
    //                 }
    //             }
    //         }
    //     }

    //     return $drawings;
    // }

    public function drawings()
    {
        $drawings = [];

        // Lấy dữ liệu từ collection()
        $data = $this->collection();

        foreach ($data as $index => $row) {
            foreach ($this->imageColumns as $imageColumn) {
                if (!empty($row->$imageColumn)) {
                    $drawing = new Drawing();
                    $drawing->setName('Image');
                    $drawing->setDescription('Image from column ' . $imageColumn);

                    // Lấy đường dẫn tuyệt đối của file ảnh
                    $imagePath = storage_path('app/public/' . $row->$imageColumn);

                    // Kiểm tra file tồn tại
                    if (file_exists($imagePath)) {
                        $drawing->setPath($imagePath); // Đường dẫn tuyệt đối
                        $drawing->setHeight(80); // Chiều cao ảnh
                        $drawing->setWidth(80);  // Chiều rộng ảnh

                        // Thiết lập vị trí ảnh
                        $columnIndex = array_search($imageColumn, $this->columns);
                        $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex + 1);
                        $rowNumber = $index + 2; // Hàng bắt đầu từ 2 (hàng đầu tiên là tiêu đề)
                        $drawing->setCoordinates($columnLetter . $rowNumber);

                        // Thiết lập căn chỉnh
                        $drawing->setOffsetX(5); // Căn lề trái 5px
                        $drawing->setOffsetY(5); // Căn lề trên 5px

                        // Thiết lập rotation (0 = không xoay)
                        $drawing->setRotation(0);

                        $drawings[] = $drawing;
                    }
                }
            }
        }

        return $drawings;
    }

    public function columnWidths(): array
    {
        // Thiết lập chiều rộng cột cho tất cả các cột
        $widths = [];
        foreach ($this->columns as $index => $column) {
            $columnLetter = chr(65 + $index); // Chuyển số cột thành chữ (A, B, C...)
            $widths[$columnLetter] = 30; // Chiều rộng cố định 30px
        }
        return $widths;
    }

    public function bindValue(Cell $cell, $value)
    {
        // Thiết lập wrap text (xuống dòng tự động) cho tất cả các ô
        $cell->setValueExplicit($value, DataType::TYPE_STRING);
        $cell->getStyle()->getAlignment()->setWrapText(true);

        return true;
    }
}
