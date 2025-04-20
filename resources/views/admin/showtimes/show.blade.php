@extends('admin.layouts.master')
@section('title', 'Chi tiết phòng')

@section('style')
    <style>
        .seat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            height: 100%;
            overflow: hidden;
        }

        .seat-item img.seat {
            width: 32px;
            height: 32px;
            /* ✅ Cố định chiều cao ảnh */
            object-fit: contain;
        }

        .seat-label,
        .seat-label-double {
            font-size: 12px;
            margin-top: 4px;
            white-space: nowrap;
            line-height: 1;
            text-align: center;
        }

        .box-item {
            width: 50px;
            height: 60px;
            /* ✅ Cố định khung ghế */
            padding: 0;
            vertical-align: middle;
        }

        .screen {
            text-align: center;
            font-weight: bold;
            border-radius: 5px;
        }

        .seat-map .seat {
            margin: 5px;
            text-align: center;
            border-radius: 5px;
            cursor: pointer;
            display: block;
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

        /* Ghế đơn */
        .seat-item img.seat {
            width: 32px;
            height: 32px;
            object-fit: contain;
        }

        .seat-item svg.seat {
            width: 32px;
            height: 32px;
            object-fit: contain;
        }

        /* Ghế đôi */
        .seat-item img.seat-double {
            width: 48px;
            height: 32px;
            object-fit: contain;
        }

        .seat-item svg.seat-double {
            width: 48px;
            height: 32px;
            object-fit: contain;
        }

        .seat-sold {
            color: red
        }
    </style>


@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Chi tiết suất chiếu</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.showtimes.index') }}">Danh sách suất chiếu</a>
                        </li>
                        <li class="breadcrumb-item active">Chi tiết suất chiếu</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Chi tiết phòng -->
    <div class="row">

        <div class="col-9">

            <div class="text-center">
                <div class="screen ">
                    <img style="width: 80% !important;" src="{{ asset('images/seat/manhinh.png') }}" alt="">
                </div>
                <div class="seat-map">
                    <table style="width: 60%" class="table-chart-chair table-none align-middle mx-auto text-center mt-3">
                        <tbody>
                            @for ($row = 0; $row < $matrix_colume; $row++)
                                <tr>
                                    {{-- <td class="box-item">{{ chr(65 + $row) }}</td> --}}
                                    @for ($col = 0; $col < $matrix_colume; $col++)
                                        @php
                                            $seatType = $seatMap[chr(65 + $row)][$col + 1][0] ?? null;
                                        @endphp
                                        @php
                                            $seatStatus = $seatMap[chr(65 + $row)][$col + 1][1] ?? null;
                                        @endphp
                                        @if ($seatType == 3)
                                            <td class="box-item" colspan="2">
                                                <div class="seat-item">

                                                    <svg class='seat seat-double {{ $seatStatus == "sold" ? 'seat-sold' : '' }}' width="60%" version="1.0"
                                                        xmlns="http://www.w3.org/2000/svg" width="512.000000pt"
                                                        height="325.000000pt" viewBox="0 0 512.000000 325.000000"
                                                        preserveAspectRatio="xMidYMid meet">

                                                        <g transform="translate(0.000000,325.000000) scale(0.100000,-0.100000)"
                                                            fill="currentColor" stroke="none">
                                                            <path d="M803 3079 c-75 -29 -155 -106 -195 -187 l-33 -67 -3 -188 c-2 -103
                                                                    -7 -186 -10 -185 -89 48 -108 53 -207 53 -82 0 -109 -4 -147 -22 -66 -30 -143
                                                                    -105 -175 -172 l-28 -56 0 -625 0 -625 26 -56 c57 -122 163 -195 297 -206 l72
                                                                    -6 0 -292 c0 -270 1 -293 18 -308 16 -15 47 -17 229 -17 182 0 212 2 224 16 8
                                                                    9 53 147 100 308 l85 291 1504 0 1504 0 85 -291 c47 -161 92 -299 100 -308 12
                                                                    -14 42 -16 226 -16 193 0 213 2 228 18 15 16 17 53 17 309 l0 290 70 6 c133
                                                                    11 235 79 292 195 l33 67 3 593 c3 643 1 666 -51 749 -33 52 -100 111 -156
                                                                    137 -37 17 -65 21 -146 21 -99 0 -118 -5 -207 -53 -3 -1 -8 82 -10 185 l-3
                                                                    188 -33 67 c-41 83 -120 158 -197 187 -55 21 -72 21 -795 21 -723 0 -740 0
                                                                    -795 -21 -33 -12 -77 -41 -110 -71 l-55 -50 -55 50 c-33 30 -77 59 -110 71
                                                                    -55 21 -71 21 -798 20 -719 0 -744 -1 -794 -20z m1521 -123 c67 -20 114 -61
                                                                    142 -120 l24 -51 0 -472 0 -473 -890 0 -890 0 0 473 0 473 26 52 c30 60 74 98
                                                                    136 117 65 21 1385 21 1452 1z m1920 0 c66 -20 110 -57 140 -118 l26 -52 0
                                                                    -473 0 -473 -890 0 -890 0 0 473 0 472 24 51 c27 58 75 100 138 119 65 21
                                                                    1385 21 1452 1z m-3763 -621 c23 -17 53 -50 66 -74 23 -44 23 -45 23 -435 0
                                                                    -360 2 -394 19 -432 14 -31 30 -47 63 -63 l44 -21 1865 0 c1825 0 1865 1 1907
                                                                    20 33 15 47 29 62 62 18 40 20 68 20 434 0 390 0 391 23 435 13 24 43 58 66
                                                                    74 39 28 50 30 126 30 76 0 87 -2 126 -30 23 -16 53 -50 66 -74 l23 -44 0
                                                                    -584 c0 -637 1 -624 -58 -687 -15 -16 -45 -37 -67 -47 -39 -18 -121 -19 -2295
                                                                    -19 -2160 0 -2257 1 -2295 19 -50 22 -101 79 -115 128 -8 26 -10 220 -8 625
                                                                    l3 586 30 43 c17 23 50 53 75 66 38 21 56 24 117 21 60 -3 78 -8 114 -33z
                                                                    m3929 -760 l0 -125 -1850 0 -1850 0 0 125 0 125 1850 0 1850 0 0 -125z m-3500
                                                                    -841 c0 -8 -105 -369 -126 -437 l-16 -48 -116 3 -117 3 -3 243 -2 242 190 0
                                                                    c104 0 190 -3 190 -6z m3678 -236 l-3 -243 -117 -3 -116 -3 -16 48 c-21 67
                                                                    -126 429 -126 437 0 3 86 6 190 6 l190 0 -2 -242z" />
                                                        </g>
                                                    </svg>

                                                    {{-- <img src="{{ asset('svg/doi.svg') }}" class='seat seat-double'
                                                        width="60%"> --}}
                                                    <p class="seat-label-double">{{ chr(65 + $row) . ($col + 1) }}
                                                        {{ chr(65 + $row) . ($col + 2) }}</p>
                                                </div>
                                            </td>
                                            <td class="box-item" style="display: none;"></td>
                                            @php $col++; @endphp
                                        @else
                                            <td class="box-item">
                                                <div class="seat-item">
                                                    @switch($seatType)
                                                        @case(1)
                                                            <svg class='seat {{ $seatStatus == "sold" ? 'seat-sold' : '' }}' width="60%" version="1.0" 
                                                                xmlns="http://www.w3.org/2000/svg" width="512.000000pt"
                                                                height="512.000000pt" viewBox="0 0 512.000000 512.000000"
                                                                preserveAspectRatio="xMidYMid meet">

                                                                <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
                                                                    fill="currentColor" stroke="none">
                                                                    <path
                                                                        d="M1424 4671 c-297 -73 -521 -322 -563 -625 -6 -43 -11 -202 -11 -355
                                                                                l0 -277 -72 -17 c-115 -27 -193 -70 -279 -156 -110 -109 -165 -229 -175 -376
                                                                                -11 -155 44 -315 150 -431 l55 -61 3 -539 c4 -588 3 -575 65 -699 33 -65 134
                                                                                -170 200 -207 l51 -29 4 -157 c4 -174 15 -212 78 -265 49 -42 91 -49 266 -45
                                                                                157 3 162 4 200 30 21 15 50 44 64 65 23 34 25 46 28 180 l3 143 1069 0 1069
                                                                                0 3 -143 c3 -134 5 -146 28 -180 14 -21 43 -50 64 -65 l39 -27 183 0 182 0 44
                                                                                30 c23 17 54 51 68 75 23 42 25 56 28 202 l4 157 51 29 c66 37 167 142 200
                                                                                207 62 124 61 111 65 699 l3 539 55 61 c106 116 161 276 150 431 -10 147 -65
                                                                                267 -175 376 -86 86 -164 129 -278 156 l-71 17 -5 325 c-4 299 -6 332 -26 401
                                                                                -65 219 -198 379 -396 475 -163 79 -102 75 -1292 74 -972 0 -1064 -2 -1129
                                                                                -18z m2215 -206 c172 -41 320 -172 384 -340 21 -57 22 -76 25 -398 l3 -339
                                                                                -23 -6 c-40 -10 -146 -69 -192 -107 -89 -73 -170 -207 -195 -323 -7 -34 -11
                                                                                -188 -11 -424 l0 -371 -57 6 c-32 4 -107 13 -168 21 -285 39 -479 50 -845 50
                                                                                -378 0 -557 -11 -879 -54 -80 -11 -156 -20 -168 -20 l-22 0 -3 398 c-4 455 -4
                                                                                455 -100 598 -71 106 -177 186 -301 228 -17 5 -18 26 -15 343 3 322 4 341 25
                                                                                398 63 166 211 298 379 339 91 22 2070 22 2163 1z m-2576 -1302 c83 -39 141
                                                                                -97 180 -181 l32 -67 5 -467 5 -468 28 -27 c35 -35 62 -35 297 0 637 96 1263
                                                                                96 1900 0 235 -35 262 -35 297 0 l28 27 5 468 5 467 32 67 c40 84 97 141 181
                                                                                181 60 29 77 32 157 32 82 0 97 -3 157 -33 153 -75 240 -242 207 -400 -18 -90
                                                                                -54 -151 -132 -224 l-72 -68 -5 -587 -5 -588 -27 -50 c-32 -60 -87 -114 -148
                                                                                -146 l-45 -24 -1585 0 -1585 0 -50 27 c-58 30 -126 100 -152 155 -16 35 -18
                                                                                88 -23 626 l-5 587 -72 68 c-99 93 -137 172 -136 287 0 102 28 176 90 245 115
                                                                                128 283 164 436 93z m217 -2418 l0 -105 -105 0 -105 0 0 105 0 105 105 0 105
                                                                                0 0 -105z m2770 0 l0 -105 -105 0 -105 0 0 105 0 105 105 0 105 0 0 -105z" />
                                                                </g>
                                                            </svg>

                                                            {{-- <img src="{{ asset('svg/thuong.svg') }}" class='seat' width="60%"> --}}
                                                            <span class="seat-label">{{ chr(65 + $row) . ($col + 1) }}</span>
                                                        @break

                                                        @case(2)
                                                            {{-- <img src="{{ asset('svg/vip.svg') }}" class='seat' width="60%"> --}}
                                                            <svg class='seat {{ $seatStatus == "sold" ? 'seat-sold' : '' }}' width="60%" version="1.0" 
                                                                xmlns="http://www.w3.org/2000/svg" width="512.000000pt"
                                                                height="512.000000pt" viewBox="0 0 512.000000 512.000000"
                                                                preserveAspectRatio="xMidYMid meet">

                                                                <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
                                                                    fill="currentColor" stroke="none">
                                                                    <path
                                                                        d="M992 5100 c-192 -51 -333 -183 -404 -380 -23 -65 -23 -67 -26 -636
                                                                                   l-3 -571 -64 -16 c-208 -51 -367 -210 -419 -418 -14 -59 -16 -192 -16 -1292
                                                                                   l0 -1227 34 -38 34 -37 126 -4 126 -3 0 -195 c0 -156 3 -200 16 -224 30 -58
                                                                                   37 -59 560 -59 529 0 527 0 558 65 13 26 16 71 16 224 l0 191 1029 0 1029 0 4
                                                                                   -202 c3 -223 7 -236 65 -263 28 -13 100 -15 528 -13 l497 3 29 33 29 32 0 204
                                                                                   0 204 123 4 c95 3 128 7 148 21 57 38 54 -33 54 1286 0 1163 -1 1219 -19 1286
                                                                                   -47 173 -144 294 -300 375 -40 21 -98 44 -129 50 l-56 13 -3 571 c-3 569 -3
                                                                                   571 -26 636 -35 98 -79 168 -147 235 -74 73 -157 119 -261 145 -75 19 -115 20
                                                                                   -1568 19 -1435 0 -1494 -1 -1564 -19z m3073 -220 c101 -27 188 -99 232 -194
                                                                                   l28 -61 3 -566 3 -566 -48 -18 c-146 -55 -267 -176 -327 -328 -35 -87 -46
                                                                                   -188 -46 -414 l0 -183 -1349 0 -1348 0 -6 243 c-5 248 -11 289 -55 387 -56
                                                                                   129 -197 258 -325 300 l-38 12 3 566 3 567 28 60 c45 96 123 165 217 191 59
                                                                                   16 2963 20 3025 4z m-3290 -1630 c108 -49 175 -140 195 -261 6 -40 10 -338 10
                                                                                   -787 0 -803 -4 -759 67 -788 48 -21 2978 -21 3026 0 71 29 67 -15 67 788 0
                                                                                   449 4 747 10 787 32 195 202 320 394 290 110 -18 204 -88 253 -188 l28 -56 3
                                                                                   -1162 2 -1163 -2270 0 -2270 0 2 1163 3 1162 28 56 c48 98 145 171 249 188 74
                                                                                   12 133 3 203 -29z m3135 -1275 l0 -345 -1350 0 -1350 0 0 345 0 345 1350 0
                                                                                   1350 0 0 -345z m-2610 -1620 l0 -125 -345 0 -345 0 0 125 0 125 345 0 345 0 0
                                                                                   -125z m3210 0 l0 -125 -345 0 -345 0 0 125 0 125 345 0 345 0 0 -125z" />
                                                                </g>
                                                            </svg>

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
        @php
            $imagePath = $showtime->movie->img_thumbnail && Storage::exists($showtime->movie->img_thumbnail)
                ? Storage::url($showtime->movie->img_thumbnail)
                : asset('images/movie/default-movie.jpg');
        @endphp
        <div class="col-3">
            <div class="card shadow-sm border-0">
                <h5 class="card-title text-center fw-bold text-primary mb-3">🎬 Thông tin suất chiếu</h5>

                <img src="{{ $imagePath }}" class="card-img-top rounded-top px-4 pb-4"
                    alt="Ảnh phim {{ $showtime->movie->name }}" style="object-fit: cover;">

                <div class="card-body px-3">
                    <div class="mb-3 text-center">
                        <h5 class="fw-bold text-dark mb-1">🎬 {{ $showtime->movie->name }}</h5>
                        <hr class="my-2">
                    </div>

                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">🏢 <strong>Chi nhánh:</strong> {{ $showtime->branch->name }}</li>
                        <li class="mb-2">🎟️ <strong>Rạp:</strong> {{ $showtime->cinema->name }}</li>
                        <li class="mb-2">🛋️ <strong>Phòng chiếu:</strong> {{ $showtime->room->name }}</li>
                        <li class="mb-2">⏰ <strong>Giờ chiếu:</strong>
                            {{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i d/m/Y') }}</li>

                        <li class="mb-2">🎫 <strong>Đã bán: <span style="color: yellowgreen">{{ $soldSeats }}</span> /  <span style="color: green">{{ $totalSeats }}</span> Ghế</strong> </li>
                           
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
