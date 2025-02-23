<?php

namespace App\Services;

use App\Models\Seat_template;
use App\Models\Type_seat;

class SeatTemplateService
{
    public function getAll($request)
    {
        $query = Seat_template::query()->latest('id');
        if ($request->has('name') && $request->query('name', '') != "") {
            $name = $request->query('name', '');
            $query->where('name', 'LIKE', "%$name%");
        }


        return  $query->paginate(10);
    }
    public function storeService(array $data)
    {
        return Seat_template::query()->create($data);
    }
    public function updateSevice(string $id, array $data)
    {
        $seatTemplate = Seat_template::query()->findOrFail($id);
        $seatTemplate->update($data);
        return $seatTemplate;
    }
    public function editService(string $id)
    {
        $seatTemplate = Seat_template::query()->findOrFail($id);
        $matrix = Seat_template::getMatrixById($seatTemplate->matrix);
        $type_seats = Type_seat::query()->get();
        $seatMap = [];
        if ($seatTemplate->seat_structure) {

            $seats = json_decode($seatTemplate->seat_structure, true);
           

            // Đếm tổng số ghế
            $totalSeats = 0; // Khởi tạo biến tổng số ghế

            if ($seats) {
                foreach ($seats as $seat) {
                    $coordinates_y = $seat['coordinates_y'];
                    $coordinates_x = $seat['coordinates_x'];

                    if (!isset($seatMap[$coordinates_y])) {
                        $seatMap[$coordinates_y] = [];
                    }

                    $seatMap[$coordinates_y][$coordinates_x] = $seat['type_seat_id'];

                    // Tăng tổng số ghế
                    if ($seat['type_seat_id'] == 3) {
                        // Ghế đôi, cộng thêm 2
                        $totalSeats += 2;
                    } else {
                        // Ghế thường hoặc ghế VIP, cộng thêm 1
                        $totalSeats++;
                    }
                }
            }
        }
        return [$seatTemplate, $matrix, $seatMap,$type_seats];
    }
}
