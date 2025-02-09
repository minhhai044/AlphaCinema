<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DynamicExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController
{
    public function index($table)
    {
        $config = config("excel.exports.custom_tables.{$table}");

        if (!$config) {
            return abort(404);
        }

        $export = new DynamicExport(
            $config['model'],
            $config['columns'],
            $config['headings'],
            $config['relations'] ?? [],
            $config['image_columns'] ?? [] // Truyền các trường ảnh (nếu có)
        );

        return Excel::download($export, "{$table}.xlsx", \Maatwebsite\Excel\Excel::XLSX);
    }
}
