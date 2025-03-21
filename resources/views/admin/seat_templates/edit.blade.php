@extends('admin.layouts.master')
@section('content')
    <style>
        .screen {
            text-align: center;
            font-weight: bold;
            border-radius: 5px;
        }
        .seat-map .seat {
            margin: 5px;
            text-align: center;
            line-height: 40px;
            border-radius: 5px;
            cursor: pointer;
        }

        .light-orange {
            background-color: #fdfdfd;
        }

        .light-blue {
            background-color: #61bfee  ;
        }

        .light-pink {
            background-color: #367cf3;
        }

        .box-item {
            width: 35px;
            height: 35px;
            max-width: 35px;
            max-height: 35px;
        }
        .box-item-show {
            width: 50px;
            height: 50px;
            max-width: 50px;
            max-height: 50px;
        }
        
        
    </style>
    <h4 class="fw-semibold">Mẫu sơ đồ ghế</h4>
     <!-- start page title -->
     {{-- <div class="row">
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
    </div> --}}
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-9 text-center">
            <div class="screen ">
                <img class="w-100" src="{{asset('images/seat/manhinh.png')}}" alt="">
            </div>
            <div class="seat-map d-flex flex-column align-items-center">

                <div style="width: 80%;" class="card border-0 ">


                    @if (!$seatTemplate->is_publish)
                        <table style="width: 95%;" class="table-chart-chair table-none align-middle mx-auto text-center mb-5">
                            <tbody>
                                @for ($row = 0; $row < $matrix['max_row']; $row++)
                                    @php
                                        $rowClass = '';
                                        $isAllRegular = $isAllVip = $isAllDouble = false;

                                        if ($row < $seatTemplate->row_regular) {
                                            $rowTypeSeat = 1;
                                            $rowClass = 'light-orange'; // Ghế thường
                                            $isAllRegular = true;
                                        } elseif ($row < $seatTemplate->row_vip + $seatTemplate->row_regular) {
                                            $rowClass = 'light-blue'; // Ghế VIP
                                            $isAllVip = true;
                                            $rowTypeSeat = 2;
                                        } else {
                                            $rowClass = 'light-pink'; // Ghế đôi
                                            $rowTypeSeat = 3;
                                            $isAllDouble = true;
                                        }
                                    @endphp
                                    <tr data-row-type-seat={{ $rowTypeSeat }}>
                                        <td class="box-item me-3">{{ chr(65 + $row) }}</td>
                                        @for ($col = 0; $col < $matrix['max_col']; $col++)
                                            @php
                                                // Kiểm tra xem ô hiện tại có trong seatMap không
                                                $seatType =
                                                    isset($seatMap[chr(65 + $row)]) &&
                                                    isset($seatMap[chr(65 + $row)][$col + 1])
                                                        ? $seatMap[chr(65 + $row)][$col + 1]
                                                        : null;
                                            @endphp
                                            @if ($seatType == 3)
                                                <!-- Nếu là ghế đôi -->
                                                <td class="box-item border-1 {{ $rowClass }}"
                                                    data-row="{{ chr(65 + $row) }}" data-col={{ $col + 1 }}
                                                    colspan="2">
                                                    <div class="box-item-seat" data-type-seat-id="3">
                                                        <!-- 3 cho ghế đôi -->
                                                        <img src="{{ asset('images/seat/seat-2.png') }}" width="50%" class='seat'>
                                                    </div>
                                                </td>
                                                <td class="box-item border-1 {{ $rowClass }}" style="display: none;"
                                                    data-row="{{ chr(65 + $row) }}" data-col={{ $col + 2 }}
                                                    data-type-seat-id="3">
                                                    <div class="box-item-seat" data-type-seat-id="3">
                                                        <img src="{{ asset('svg/seat-add.svg') }}" class='seat'
                                                            width="50%">

                                                    </div>
                                                </td>
                                                @php $col++; @endphp
                                            @else
                                                <td class="box-item border-1 {{ $rowClass }}"
                                                    data-row="{{ chr(65 + $row) }}" data-col={{ $col + 1 }}>
                                                    <div class="box-item-seat" data-type-seat-id="{{ $rowTypeSeat }}">
                                                        @switch($seatType)
                                                            @case(1)
                                                                <img src="{{ asset('images/seat/seat-1.png') }}" class='seat'
                                                                    width="70%">
                                                            @break

                                                            @case(2)
                                                                <img src="{{ asset('images/seat/seat-1.png') }}" class='seat'
                                                                    width="70%">
                                                            @break

                                                            @default
                                                                <img src="{{ asset('svg/seat-add.svg') }}" class='seat'
                                                                    width="60%">
                                                        @endswitch
                                                    </div>
                                                </td>
                                            @endif
                                        @endfor
                                        <td class='box-item border-1'>
                                            <button type="button" style="width: 90%;height: 90%;"
                                                class="btn btn-info btn-select-all btn-sm "
                                                data-row="{{ chr(65 + $row) }}">
                                                <i class="bx bx-plus-medical"></i>
                                            </button>
                                        </td>
                                        <td class='box-item border-1'>
                                            <button type="button" style="width: 90%;height: 90%;"
                                                class="btn btn-danger btn-remove-all btn-sm "
                                                data-row="{{ chr(65 + $row) }}"> <i
                                                    class="mdi mdi-trash-can-outline "></i></button>

                                        </td>

                                    </tr>
                                @endfor
                            </tbody>

                        </table>
                    @else
                    <table style="width: 95%;" class="table-chart-chair table-none align-middle mx-auto text-center mb-5">
                        <tbody>
                            @for ($row = 0; $row < $matrix['max_row']; $row++)
                                <tr>
                                    <td class="box-item">{{ chr(65 + $row) }}</td>
                                    @for ($col = 0; $col < $matrix['max_col']; $col++)
                                        @php
                                            // Kiểm tra xem ô hiện tại có trong seatMap không
                                            $seatType =
                                                isset($seatMap[chr(65 + $row)]) &&
                                                isset($seatMap[chr(65 + $row)][$col + 1])
                                                    ? $seatMap[chr(65 + $row)][$col + 1]
                                                    : null;
                                        @endphp
                                        @if ($seatType == 3)
                                            <!-- Nếu là ghế đôi -->
                                            <td class="box-item-show" colspan="2">
                                                <div class="seat-item">
                                                    <!-- 3 cho ghế đôi -->
                                                    <img src="{{ asset('images/seat/seat-2-booked.png') }}"
                                                        class='seat' width="60%">
                                                    <p
                                                        class="seat-label-double">{{ chr(65 + $row) . ($col + 1) }}
                                                        {{ chr(65 + $row) . ($col + 2) }}</p>
                                                </div>
                                            </td>
                                            <td class="box-item-show" style="display: none;">
                                                <div class="seat-item">
                                                    <img src="{{ asset('svg/seat-add.svg') }}" class='seat'
                                                        width="60%">
                                                </div>
                                            </td>
                                            @php $col++; @endphp
                                        @else
                                            <td class="box-item-show">
                                                <div class="seat-item">
                                                    @switch($seatType)
                                                        @case(1)
                                                            <img src="{{ asset('images/seat/seat-1.png') }}"
                                                                class='seat' width="70%">
                                                            <span
                                                                class="seat-label">{{ chr(65 + $row) . $col + 1 }}</span>
                                                        @break

                                                        @case(2)
                                                            <img src="{{ asset('images/seat/seat-1-free.png') }}" class='seat'
                                                                width="70%">
                                                            <span
                                                                class="seat-label">{{ chr(65 + $row) . $col + 1 }}</span>
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
                    @endif

                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="col-md-12">
                <div class="card card-seat ">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Thông tin thêm</h4>
                    </div><!-- end card header -->
                    <div class="card-body ">
                        <table class="table table-borderless   align-middle mb-0">
                            @if ($seatTemplate->is_publish == true)
                                <tbody>
                                    <tr>
                                        <td>Ghế thường</td>
                                        <td class="text-center"> <img src="{{ asset('images/seat/seat-1.png') }}"
                                                height="30px">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Ghế vip</td>
                                        <td class="text-center"> <img src="{{ asset('images/seat/seat-1-free.png') }}"
                                                height="30px">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Ghế đôi</td>
                                        <td class="text-center"> <img src="{{ asset('images/seat/seat-2-booked.png') }}"
                                                height="30px">
                                        </td>
                                    </tr>

                                    {{-- <tr class="table-active">
                                        <th colspan='2' class="text-center">Tổng
                                            {{ $totalSeats }} chỗ ngồi</th>
                                    </tr> --}}

                                </tbody>
                            @else
                                <tbody>
                                    <tr>
                                        <td>Hàng ghế thường</td>
                                        <td class="text-center">
                                            <div class='box-item border light-orange'></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Hàng ghế vip</td>
                                        <td class="text-center">
                                            <div class='box-item border light-blue'></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Hàng ghế đôi</td>
                                        <td class="text-center">
                                            <div class='box-item border light-pink'></div>
                                        </td>

                                </tbody>
                            @endif

                        </table>
                    </div>
                </div>
            </div>
            @if ($seatTemplate->is_publish)
            <form action="{{ route('admin.update_seat.seat_templates', $seatTemplate) }}" method="post">
                @csrf
                @method('PUT')
                <input type="hidden" name="is_publish" value="0">
                <input type="hidden" name="is_active" value="0">
                <button type="submit" class="btn btn-primary">Thay đổi cấu trúc ghế</button>
                <a href="{{route('admin.index.seat_templates')}}"><button type="button" class="btn btn-warning">Quay lại</button></a>
            </form>
           
            @else
            <form id="seatForm" action="{{ route('admin.update_seat.seat_templates', $seatTemplate) }}" method="post">
                @csrf
                @method('PUT')
            
                <input type="hidden" name="seat_structure" id="seatStructure">
                <input type="hidden" name="is_publish" value="1">
                <button type="submit" class="btn btn-info">Cập nhật sơ đồ ghế</button>
               
               
                <a href="{{route('admin.index.seat_templates')}}"><button type="button" class="btn btn-warning">Quay lại</button></a>
            </form>
            @endif
           
        </div>
    </div>

   

@endsection
@section('script')
<script>
    $(document).ready(function() {
        // Hàm kiểm tra ghế kế bên có đủ điều kiện để tạo ghế đôi hay không
        function canMakeDoubleSeat($td) {
            return $td.length && $td.find('img').attr('src')?.includes('seat-add.svg') && $td.css('display') !==
                'none';
        }

        $('.box-item-seat').on('click', function() {
            //Trỏ đến img trong class box-item-seat
            var img = $(this).find('img');
            // Lấy src của image đó 
            var currentSrc = img.attr('src');
            // Lấy typeSeatId từ data-attribute 1 : Ghế thường , 2 : ghế Vip , 3 Ghế đôi
            var typeSeatId = parseInt($(this).data('typeSeatId'));

            // Tìm và trỏ đến thẻ td đang click
            var tdElement = $(this).closest('td');
            // Lấy td bên phải
            var nextTd = tdElement.next();

            // Lấy td bên trái
            var previousTd = tdElement.prev();

            // Nếu bạn chưa chọn ghế thì currentSrc sẽ chứa seat-add.svg
            // Nếu bạn đã chọn ghế thì currentSrc sẽ chứa seat-regular.svg hoặc seat-vip.svg hoặc seat-double.svg


            if (currentSrc.includes('seat-add.svg')) {
                // Ghế đôi typeSeatId = 3 
                if (typeSeatId === 3) {

                    if (canMakeDoubleSeat(nextTd)) {

                        // Chọn ghế đôi bên phải
                        tdElement.attr('colspan', 2); // set colSpan = 2 cho td đang click
                        nextTd.hide(); // ẩn td bên phải
                        img.attr('src', "{{ asset('images/seat/seat-2.png') }}").css('width', '50%'); // set src cho img
                    } else if (canMakeDoubleSeat(
                            previousTd)) { // nếu đằng trước td không còn thì sẽ quay lại đằng sau td
                        // Chọn ghế đôi bên trái
                        previousTd.attr('colspan', 2); // set colSpan = 2 cho td trước td đang click
                        tdElement.hide(); // ẩn td bên trái
                        previousTd.find('img').attr('src',
                            "{{ asset('images/seat/seat-2.png') }}").css('width', '50%'); // set src cho img
                    } else {
                        // nếu đằng trước và sau td không còn thì sẽ thông báo
                        toastr.error('Không có đủ chỗ trống để đặt ghế đôi !!');
                    }
                } else {
                    // Xử lý ghế thường và ghế VIP
                    var seatSrc = (typeSeatId === 1) ? "{{ asset('images/seat/seat-1.png') }}" :
                        "{{ asset('images/seat/seat-1.png') }}";
                    img.attr('src', seatSrc).css('width', '70%');
                }
            } else {
                // Trả ghế về trạng thái ban đầu
                img.attr('src', "{{ asset('svg/seat-add.svg') }}").css('width', '60%');

                // Nếu ghế hiện tại là ghế đôi, trả colSpan về 1 và hiển thị lại ghế kế bên
                if (typeSeatId === 3 && tdElement.attr('colspan') == 2) {
                    if (nextTd.length) {
                        tdElement.attr('colspan', 1);
                        nextTd.show();
                    } else if (previousTd.length) {
                        previousTd.attr('colspan', 1);
                        tdElement.show();
                    }
                }
            }
        });
        // Xóa bỏ tất cả các ghế
        $('.btn-remove-all').on('click', function() {
            // Lấy  row đó A or B or C ,.....
            var row = $(this).data('row');
            // Trỏ đến các td có data-row = row và có class = box-item-seat
            var seats = $(`td[data-row="${row}"] .box-item-seat`);
            // Lặp qua các seats đó
            seats.each(function() {
                // Lấy hình ảnh của từng td
                var img = $(this).find('img');
                // Thêm ảnh vào các thẻ image
                img.attr('src', "{{ asset('svg/seat-add.svg') }}").css('width', '60%');
                // Lấy thông tin td hiện tại
                var tdElement = $(this).closest('td');
                // Ghế đôi  sẽ trở về dạng ban đầu
                if (tdElement.attr('colSpan') == 2) {
                    var nextTd = tdElement.next();
                    tdElement.attr('colSpan', 1);
                    nextTd.show();
                }
            });
        });
        // Chọn tất cả các ghế
        $('.btn-select-all').on('click', function() {
            // Lấy  row đó A or B or C ,.....
            var row = $(this).data('row');
            // Trỏ đến các td có data-row = row và có class = box-item-seat
            var seats = $(`td[data-row="${row}"] .box-item-seat`);
            // Lấy type của hàng ghế đó
            var seatType = parseInt($(this).closest('tr').data('row-type-seat'));
            // Reset lại toàn bộ các ghế đã click trước
            resetAllSeats(seats);
            // Nếu seatType == 1 thì sẽ setAtribu cho image
            if (seatType === 1) {
                seats.each(function() {
                    $(this).find('img').attr('src', "{{ asset('images/seat/seat-1.png') }}").css('width', '70%');
                });
                // Nếu seatType == 2 thì sẽ setAtribu cho image
            } else if (seatType === 2) {
                seats.each(function() {
                    $(this).find('img').attr('src', "{{ asset('images/seat/seat-1.png') }}").css('width', '70%');
                });
                // Nếu seatType == 3 thì sẽ setAtribu cho image

            } else if (seatType === 3) {
                for (let i = 0; i < seats.length; i++) {
                    // trỏ đến phần tử thứ i trong seats
                    var seatDiv = seats.eq(i);
                    // lấy ảnh trong phần tử đó
                    var img = seatDiv.find('img');
                    // trỏ đến id đang click
                    var tdElement = seatDiv.closest('td');
                    // lấy td tiếp theo
                    var nextTd = tdElement.next();
                    // nếu nextTd chưa đc chọn 
                    if (canMakeDoubleSeat(nextTd)) {
                        tdElement.attr('colSpan', 2);
                        nextTd.hide();
                        img.attr('src', "{{ asset('images/seat/seat-2.png') }}");
                        i++;
                        // kiểm tra td sau đó đã đc chọn hay chưa
                    } else if (i > 0 && canMakeDoubleSeat(seats.eq(i - 1).closest('td'))) {
                        var previousTd = seats.eq(i - 1).closest('td');
                        previousTd.attr('colSpan', 2);
                        tdElement.hide();
                        previousTd.find('img').attr('src', "{{ asset('images/seat/seat-2.png') }}");
                    }
                }
            }
        });

        function resetAllSeats(seats) {
            seats.each(function() {
                var img = $(this).find('img');
                var tdElement = $(this).closest('td');

                img.attr('src', "{{ asset('svg/seat-add.svg') }}").css('width', '50%');

                if (tdElement.attr('colSpan') == 2) {
                    tdElement.attr('colSpan', 1);
                    tdElement.next().show();
                }
            });
        }
        let type_seats = @json($type_seats);
    
        $('#seatForm').submit(function(e) {
            let seatStructure = [];

            $('.box-item-seat').each(function(index) {
              
               
                var img = $(this).find('img');
                if (!img.attr('src').includes('seat-add.svg')) {
                    let tdElement = $(this).closest('td');
                    let coordinates_x = tdElement.data('col');
                    let coordinates_y = tdElement.data('row');
                    let type_seat_id = $(this).data('type-seat-id');

                    seatStructure.push({
                        id:index+1,
                        user_id : null,
                        price: type_seats.filter((item)=> item.id == type_seat_id)[0].price,
                        coordinates_x: coordinates_x,
                        coordinates_y: coordinates_y,
                        type_seat_id: type_seat_id,
                        status:'available',
                        hold_expires_at : 10
                    });
                }
            });

            if (seatStructure.length === 0) {
                e.preventDefault(); // Ngăn form submit nếu không có ghế nào được chọn
                toastr.error('Bạn chưa chọn ghế nào!!!');
                return;
            }

            $('#seatStructure').val(JSON.stringify(seatStructure));
        });
    });
</script>
@endsection