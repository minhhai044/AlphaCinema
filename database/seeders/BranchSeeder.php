<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Branch;


class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Danh sách chi nhánh và phụ thu tương ứng
        $branches = [
            ['name' => 'Hà Nội', 'surcharge' => 20000],
            ['name' => 'Hồ Chí Minh', 'surcharge' => 30000],
            ['name' => 'Đà Nẵng', 'surcharge' => 15000],
            ['name' => 'Hải Phòng', 'surcharge' => 25000],
        ];

        // Vòng lặp để thêm từng chi nhánh
        foreach ($branches as $branch) {
            DB::table('branches')->insert([
                'name' => $branch['name'],
                'slug' => Str::slug($branch['name']) . '-' . Str::ulid(),
                'surcharge' => $branch['surcharge'],
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
