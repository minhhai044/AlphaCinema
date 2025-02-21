@extends('admin.layouts.master')
@section('title', 'Chi tiết phòng')

@section('style')
    <style>
        .table-bordered th, .table-bordered td {
            text-align: center;
            vertical-align: middle;
        }
        .badge-active {
            background-color: green;
            padding: 5px 10px;
            color: white;
            border-radius: 5px;
        }
        .badge-inactive {
            background-color: red;
            padding: 5px 10px;
            color: white;
            border-radius: 5px;
        }
        .screen {
            width: 80%;
            margin: auto;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            background: gray;
            color: white;
            padding: 10px;
            border-radius: 5px;
        }
        .seat-map {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .seat-item {
            position: relative;
            text-align: center;
        }
        .seat-label, .seat-label-double {
            font-size: 14px;
            font-weight: bold;
            color: #555;
        }
        .box-item {
            padding: 5px;
            width: 35px;
            height: 35px;
            max-width: 35px;
            max-height: 35px;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Chi tiết phòng</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.rooms.index') }}">Danh sách phòng</a>
                        </li>
                        <li class="breadcrumb-item active">Chi tiết phòng</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Chi tiết phòng -->
    <div class="row">

        <div class="col-12 mb-3">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Tên phòng</th>
                        <th>Chi nhánh</th>
                        <th>Rạp phim</th>
                        <th>Loại phòng</th>
                        <th>Hoạt động</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $room->name }}</td>
                        <td>{{ $room->branch->name ?? 'Không có chi nhánh' }}</td>
                        <td>{{ $room->cinema->name ?? 'Không có rạp' }}</td>
                        <td>{{ $room->type_room->name ?? 'Không có loại phòng' }}</td>
                        <td>
                            @if ($room->is_active)
                                <span class="badge badge-active">Hoạt động</span>
                            @else
                                <span class="badge badge-inactive">Ngừng hoạt động</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Bản đồ ghế -->
        <div class="col-12">
        <h4 class="mb-sm-0 font-size-18">Mẫu ghế</h4>

            <div class="text-center">
                <div class="screen w-full mx-auto mb-4 p-2 bg-dark text-white">Màn Hình Chiếu</div>
                <div class="seat-map" >
                    <table style="width: 60%" class="table-chart-chair table-none align-middle mx-auto text-center mt-3">
                        <tbody>
                            @for ($row = 0; $row < $room->matrix_colume; $row++)
                                <tr>
                                    <td class="box-item">{{ chr(65 + $row) }}</td>
                                    @for ($col = 0; $col < $room->matrix_colume; $col++)
                                        @php
                                            $seatType = $seatMap[chr(65 + $row)][$col + 1] ?? null;
                                        @endphp
                                        @if ($seatType == 3)
                                            <td class="box-item" colspan="2">
                                                <div class="seat-item">
                                                    <img src="{{ asset('images/seat/seat-2-booked.png') }}" class='seat' width="77%">
                                                    <p class="seat-label-double">{{ chr(65 + $row) . ($col + 1) }} {{ chr(65 + $row) . ($col + 2) }}</p>
                                                </div>
                                            </td>
                                            <td class="box-item" style="display: none;"></td>
                                            @php $col++; @endphp
                                        @else
                                            <td class="box-item">
                                                <div class="seat-item">
                                                    @switch($seatType)
                                                        @case(1)
                                                            <img src="{{ asset('images/seat/seat-1.png') }}" class='seat' width="77%">
                                                            <span class="seat-label">{{ chr(65 + $row) . ($col + 1) }}</span>
                                                        @break
                                                        @case(2)
                                                            <img src="{{ asset('images/seat/seat-1-free.png') }}" class='seat' width="77%">
                                                            <span class="seat-label">{{ chr(65 + $row) . ($col + 1) }}</span>
                                                        @break
                                                    @endswitch
                                                </div>
                                            </td>
                                        @endif
                                    @endfor
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
