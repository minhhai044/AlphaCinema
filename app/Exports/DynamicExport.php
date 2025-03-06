<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Cell\Cell;


class DynamicExport implements FromCollection, WithHeadings, WithColumnWidths, WithDrawings, WithMapping, WithEvents, WithCustomValueBinder
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
        $query = $this->model->query();
        $joins = []; // Mảng để lưu các bảng đã JOIN

        foreach ($this->columns as $column) {
            if (strpos($column, '.') !== false) {
                [$relation, $relatedColumn] = explode('.', $column);

                // Nếu bảng chưa JOIN, thêm vào
                if (!isset($joins[$relation])) {
                    $relatedModel = $this->model->$relation()->getRelated();
                    $relatedTable = $relatedModel->getTable(); // Lấy tên bảng từ model liên quan

                    // Dùng alias để tránh trùng lặp
                    $alias = $relation . '_table';
                    $query->leftJoin(
                        "{$relatedTable} AS {$alias}",
                        "{$alias}.id",
                        '=',
                        "{$this->model->getTable()}.{$relation}_id"
                    );

                    $joins[$relation] = $alias;
                }

                // Thêm cột từ bảng liên quan
                $alias = $joins[$relation];
                $query->addSelect("{$alias}.{$relatedColumn} as {$relation}_{$relatedColumn}");
            } else {
                // Thêm cột từ bảng chính
                $query->addSelect("{$this->model->getTable()}.$column");
            }
        }

        return $query->get();
    }
    public function headings(): array
    {
        return $this->headings;
    }

    public function map($row): array
    {

        $data = [];
        foreach ($this->columns as $column) {
            $columnKey = str_replace('.', '_', $column);

            if (in_array($column, $this->imageColumns)) {
                $data[] = ''; // Bỏ qua ảnh
            } else {
                $data[] = data_get($row, $columnKey, ''); // Nếu không tìm thấy, trả về chuỗi rỗng
            }
        }
        return $data;
    }



    public function drawings()
    {
        $drawings = [];
        $rows = $this->collection(); // Lấy dữ liệu từ collection()
        // dd($rows);
        foreach ($rows as $index => $row) {
            foreach ($this->imageColumns as $imageColumn) {
                if (!empty($row->$imageColumn)) {
                    $drawing = new Drawing();
                    $drawing->setName('Image');
                    $drawing->setDescription('Image');
                    // Đường dẫn ảnh
                    $imagePath = storage_path("app/public/" . $row->$imageColumn);
                    if (file_exists($imagePath)) {
                        $drawing->setPath($imagePath);
                        // Kích thước ảnh (tùy chỉnh)
                        $drawing->setHeight(80);
                        $drawing->setResizeProportional(true);
                        // Xác định vị trí ảnh trong ô
                        $columnIndex = array_search($imageColumn, $this->columns) + 1;
                        $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex);
                        $rowNumber = $index + 2; // Dữ liệu bắt đầu từ dòng 2 (dòng 1 là tiêu đề)
                        $drawing->setCoordinates($columnLetter . $rowNumber);
                        // Căn chỉnh ảnh nằm chính giữa ô
                        $drawing->setOffsetX(10);
                        $drawing->setOffsetY(5);
                        // Gán ảnh vào sheet (Bắt buộc)
                        $drawings[] = $drawing;
                    } else {
                        Log::error("Ảnh không tồn tại: " . $imagePath);
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

    // use RegistersEventListeners;

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $rows = $this->collection(); // Lấy dữ liệu từ collection()
    
                // 💡 Lấy số cột & dòng
                $columnCount = count($this->columns);
                $rowCount = count($rows) + 1; // +1 vì có hàng tiêu đề
    
                // 📌 Cấu hình tiêu đề
                $headerRange = 'A1:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnCount) . '1';
                $sheet->getStyle($headerRange)->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFCCCC']],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER // 🔥 Căn giữa dọc
                    ],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4CAF50']]
                ]);
    
                // 📏 Căn giữa tất cả ô theo cả chiều ngang & dọc
                $dataRange = 'A2:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnCount) . $rowCount;
                $sheet->getStyle($dataRange)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER // 🔥 Căn giữa dọc
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
                    ]
                ]);
    
                // 🖼️ Tự động chỉnh hàng cho ảnh
                foreach ($rows as $index => $row) {
                    $rowNumber = $index + 2; // Dữ liệu bắt đầu từ dòng 2
                    $hasImage = false;
                    
                    foreach ($this->imageColumns as $imageColumn) {
                        if (!empty($row->$imageColumn)) {
                            $hasImage = true;
                            break;
                        }
                    }
    
                    // Nếu có ảnh -> đặt chiều cao lớn hơn
                    $sheet->getRowDimension($rowNumber)->setRowHeight($hasImage ? 90 : 25);
                }
            },
        ];
    }
    




    public function bindValue(Cell $cell, $value)
    {
        // Nếu giá trị là một mảng, chuyển thành chuỗi
        if (is_array($value)) {
            $value = implode(", ", $value); // Chuyển mảng thành chuỗi, ngăn cách bởi dấu phẩy
        }

        // Thiết lập wrap text (xuống dòng tự động)
        $cell->setValueExplicit($value, DataType::TYPE_STRING);
        $cell->getStyle()->getAlignment()->setWrapText(true);

        return true;
    }
}
