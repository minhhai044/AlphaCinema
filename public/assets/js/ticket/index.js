$(document).ready(function () {
    // Hàm định dạng tiền tệ
    const formatCurrency = (number) => {
        return new Intl.NumberFormat('vi-VN', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(number);
    };

    var ticketID = null;


    // Xử lý in vé
    $(document).on("click", ".printTicket", async function () {
        ticketID = $(this).data("id");

        try {
            let response = await $.ajax({
                url: `/admin/print/tickets/${ticketID}`,
                type: "GET"
            });

            if (!response.data) throw new Error('Dữ liệu vé không tồn tại.');

            const data = response.data;
            $("#ticketContainer").empty();

            const seatCount = data.ticket_seats ? data.ticket_seats.length : 0;
            const pricePerSeat = seatCount > 0 ? (data.ticket.total_price / seatCount) : (data
                .ticket.total_price || 0);

            let total = data.ticket_seats.reduce((sum, seat) => sum + seat.price, 0);
            const discount = data.voucher_type == 0 ? data.voucher_discount : 0
            total = total - discount - data.point_discount;
            console.log(data);

            if (data.ticket_seats && data.ticket_seats.length > 0) {
                data.ticket_seats.forEach((seat, index) => {
                    const ticketHtml = `
                            <div class="ticket-item mb-3 py-3 ${index > 0 ? 'mt-4' : ''}">
                                <h3 class="text-center fw-bold pb-3">Vé xem phim</h3>
                                <div class="mb-1">
                                    <div class="mb-1 fs-5 fw-semibold">Chi nhánh công ty Alpha Cinema tại ${data.branch || ''}</div>
                                    <div class="mb-1 ">MST: 012147901412</div>
                                    <div>Nhân viên in vé:  ${data.userPrintTicket} </div>
                                </div>
                                <hr class="dashed-hr">
                                <div class="mb-1">
                                    <h5 class="fw-semibold">Alpha Cinema ${data.cinema || ''}</h5>
                                    <div class="mb-1">${data.address || ''}</div>
                                </div>

                                <div class="my-3">
                                    <hr class="dashed-double">
                                    <hr class="dashed-double">
                                </div>

                                <div class="mb-1">
                                    <h4 class="fw-bold">${data.movie || ''}</h4>
                                    <div class="row">
                                        <div class="col-6 fs-5 fw-medium">${data.start_time || ''} - ${data.end_time || ''} </div>
                                        <div class= "col-6 fs-5 fw-medium"> ${data.showtime || ''}</div>
                                    </div>
                                </div>
                                <div class="mb-1 row">
                                    <div class="mb-1 col-6 fw-medium fs-5">Phòng: ${data.room || ''}</div>
                                    <div class="mb-1 col-6 fw-bold fs-4"> ${seat.seat_name || ''}</div>
                                </div>

                                <div class="my-3">
                                    <hr class="dashed-double">
                                    <hr class="dashed-double">
                                </div>

                                <div class="row me-2">
                                    <h5 class="fw-semibold col-8">Giá vé </h5>
                                    <h5 class="fw-medium col-1">VNĐ</h5>
                                    <h5 class="fw-medium fs-5 col-3 text-end"> ${formatCurrency(seat.price)} </h5>
                                </div>

                                <hr class="dashed-hr">

                                ${data.point_discount > 0 ? `<div class="mb-1 row me-2 align-items-center">
                                    <div class="fw-medium col-8"> Điểm Alpha </div>
                                    <div class="fw-medium fs-5 col-1">VNĐ</div>
                                    <div class="col-3 text-end">
                                        ${formatCurrency(data.point_discount)}
                                    </div>
                                </div>` : ''}

                                ${data.voucher_type == 0 ? `<div class="mb-1 row me-2 align-items-center">
                                        <div class="fw-medium col-8 ">Khuyến mãi </div>
                                        <div class="fw-medium fs-5 col-1">VNĐ</div>
                                        <div class="col-3 text-end">${formatCurrency(data.voucher_discount)}</div>
                                    </div>` : ''}

                                <div class="mb-5 row me-2">
                                    <h5 class="fw-bold col-8">Tổng tiền </h5>
                                    <h5 class="fw-medium col-1">VNĐ</h5>
                                    <h5 class="fw-medium fs-5 col-3 text-end"> ${formatCurrency((data.price_percentage.price_ticket_percentage - discount - data.point_discount) * (1 + data.vat/100) )}</h5>
                                    <div class="col-12 text-end">(Bao gồm ${data.vat} % VAT)</div>
                                </div>

                                 <div class="mb-1 d-flex flex-column align-items-center text-center">
                                    <div> ${data.barcode}</div>
                                    <div class="">893 ${data.code}</div>
                                </div>
                            </div>
                        `;
                    $("#ticketContainer").append(ticketHtml);
                });
            } else {
                $("#ticketContainer").html("<p>Không có thông tin ghế nào được tìm thấy.</p>");
            }

            var myModal = new bootstrap.Modal($('#ticketModal'));
            myModal.show();
        } catch (error) {
            console.error("Lỗi khi lấy dữ liệu vé:", error);
            alert('Có lỗi xảy ra khi lấy dữ liệu vé. Vui lòng thử lại sau.');
        }
    });

    // Xử lý in combo
    $(document).on("click", ".printCombo", async function () {
        ticketID = $(this).data("id");

        try {
            let response = await $.ajax({
                url: `/admin/print/tickets/combo/${ticketID}`,
                type: "GET"
            });

            if (!response.data) throw new Error('Dữ liệu combo không tồn tại.');

            const data = response.data;
            $("#ticketContainer").empty();

            let totalComboPrice = 0;
            let totalFoodPrice = 0;
            let discount = 0;
            let totalPrice = 0;
            const voucher_discount = data.voucher_type == 1 ? data.voucher_discount : 0;

            if (data.ticket_combos && data.ticket_combos.length > 0) {
                data.ticket_combos.forEach(combo => {
                    totalComboPrice += (combo.price_sale ? combo.price_sale : combo.price) * (combo.quantity || 0);
                    // if (combo.foods && combo.foods.length > 0) {
                    //     combo.foods.forEach(item => {
                    //         totalFoodPrice += (item.price || 0) * (item
                    //             .quantity || 0);
                    //     });
                    // }
                });
            }

            if (data.ticket_foods && data.ticket_foods.length > 0) {
                data.ticket_foods.forEach(food => {
                    const price = parseInt(food.price) || 0;
                    const quantity = parseInt(food.quantity) || 0;
                    const subtotal = price * quantity;

                    console.log(`Món: ${food.name} | Giá: ${price} | SL: ${quantity} | Tổng: ${subtotal}`);
                    totalFoodPrice += subtotal;
                });
            }

            console.log(data);

            // discount = (totalComboPrice + totalFoodPrice) * data?.discount;
            totalPrice = totalComboPrice + totalFoodPrice;
            console.log(totalComboPrice, totalFoodPrice);

            const ticketHtml = `
                    <div class="ticket-item mb-3">
                        <h3 class="text-center fw-bold pb-3">Hóa Đơn Đồ Ăn</h3>
                        <div class="mb-1">
                            <div class="mb-1 fs-5 fw-semibold">Chi nhánh công ty Alpha Cinema tại ${data.branch || ''}</div>
                            <div class="mb-1 ">MST: 012147901412</div>
                            <div>Nhân viên in vé:  ${data.userPrintTicket} </div>
                        </div>
                        <hr class="dashed-hr">
                        <div class="mb-1">
                            <h5 class="fw-semibold">Alpha Cinema ${data.cinema || ''}</h5>
                            <div class="mb-1">${data.address || ''}</div>
                        </div>

                        <div class="my-3">
                            <hr class="dashed-double">
                            <hr class="dashed-double">
                        </div>

                         ${data.ticket_combos && data.ticket_combos.length > 0 ? data.ticket_combos.map(combo => `
                            <div class="row">
                                <div class="fw-semibold col-6">${combo.name || ''}</div>
                                <div class="col-2 text-center">${combo.quantity || 0}</div>
                                <div class=" col-4 text-end">${formatCurrency(combo.price_sale ? combo.price_sale : combo.price)} </div>
                            </div>

                            <div class="row mb-1">
                            ${combo.foods && combo.foods.length > 0 ? combo.foods.map(item => `
                                <div class="col-6 ps-4"> • ${item.name || ''}</div>
                                 <div class="col-2 text-center">${item.quantity || 0}</div>
                            `).join('') : ''}
                            </div>
                        `).join('') : ''}
                        ${data.ticket_foods && data.ticket_foods.length > 0 ? data.ticket_foods.map(food => `

                            <div class="row">
                                <div class="mb-1 fw-semibold col-6">${food.name || ''}</div>
                                <div class="mb-1  col-2 text-center">${food.quantity || 0}</div>
                                <div class="mb-1  col-4 text-end">${formatCurrency(food.price)} </div>
                            </div>

                        `).join('') : ''}

                        <div class="my-3">
                            <hr class="dashed-double">
                            <hr class="dashed-double">
                        </div>

                         ${data.voucher_type == 1 ? `<div class="mb-1 row align-items-center">
                                        <div class="fw-medium col-6 ">Khuyến mãi </div>
                                        <div class="fw-medium  col-2 text-center">VNĐ</div>
                                        <div class="col-4 text-end">${formatCurrency(data.voucher_discount)}</div>
                                    </div>` : ''}

                        <div class="mb-1 row">
                            <div class="fw-bold col-6 fs-5">Tổng tiền </div>
                            <div class="fw-medium  col-2 text-center">VNĐ</div>
                            <div class="fw-medium fs-5 col-4 text-end"> ${formatCurrency((data.price_percentage.price_food_percentage - voucher_discount) * (1 + data.vat/100) )}</div>

                            <div class="col-12 text-end">(Bao gồm ${data.vat} % VAT)</div>
                        </div>

                         <hr class="dashed-hr">
                         <div class="mb-1 d-flex flex-column align-items-center text-center">
                            <div> ${data.barcode}</div>
                            <div class="">893 ${data.code}</div>
                        </div>

                    </div>
                `;
            $("#ticketContainer").append(ticketHtml);

            var myModal = new bootstrap.Modal($('#ticketModal'));
            myModal.show();
        } catch (error) {
            console.error("Lỗi khi lấy dữ liệu combo:", error);
            alert('Có lỗi xảy ra khi lấy dữ liệu combo. Vui lòng thử lại sau.');
        }
    });

    // Thêm sự kiện click cho nút in
    $("#printAllTickets").on("click", function () {

        // Lưu lại nội dung và tiêu đề gốc
        const originalContent = $("body").html();
        const originalTitle = document.title;
        const printContent = $("#ticketContainer").html();

        // Tạo tên file có định dạng thời gian hợp lệ
        const now = new Date();
        const day = String(now.getDate()).padStart(2, '0');
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const year = now.getFullYear();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');

        // Đặt tên file với định dạng Ve_DDMMYYYY_HHMM
        const fileName = `Ve_${day}${month}${year}_${hours}${minutes}`;

        // Thiết lập tiêu đề trang - điều này sẽ ảnh hưởng đến tên file mặc định
        document.title = fileName;

        // Thêm thẻ meta để gợi ý tên file (hoạt động với một số trình duyệt)
        $("head").append(`<meta name="filename" content="${fileName}.pdf">`);

        $("body").html(`
            <div class="print-container">
                <style>
                    @media print {
                        .ticket-item {
                            page-break-after: auto;
                            margin-bottom: 0;
                        }
                        .ticket-item:not(:last-child) {
                            page-break-after: always;
                        }
                        .dashed-hr {
                            border-top: 1px dashed #000;
                        }
                        .no-print {
                            display: none;
                        }
                        .print-container {
                            background-image: url("/logo/backgound.svg"); /* Thêm ảnh nền tại đây */
                            background-size: cover;
                            background-position: center;
                        }
                        body {
                            font-family: Arial, sans-serif;
                        }
                        .ticket-item {
                            padding: 15px;
                            margin-bottom: 20px;
                        }
                        .print-container {
                            min-height: 100vh;
                            height: auto;
                        }
                    }
                </style>
                <!-- Nội dung in -->
                ${printContent}
            </div>
        `);


        // Hiển thị thông báo cho người dùng
        console.log("Khi lưu file, tên file mặc định sẽ là: " + fileName + ".pdf");

        // Chờ một chút để đảm bảo tiêu đề đã được cập nhật
        setTimeout(function () {
            window.print();

            // Khôi phục tiêu đề gốc trước khi tải lại trang
            document.title = originalTitle;

            changeStatus(event, ticketID);

            window.onafterprint = function () {
                console.log("IN thành công");
            };
            window.location.reload();
        }, 50);
    });

    function changeStatus(event, ticketId) {

        // const status = checkbox.checked ? 'confirmed' : 'pending';
        const staff = 1;

        // Kiểm tra dữ liệu đầu vào

        $.ajax({
            url: '/admin/tickets/change-status',
            method: 'POST',
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            data: {
                ticket_id: ticketId,
                status: 'confirmed',
                staff: staff,
            },
            success: function (response) {

            },
            error: function (xhr) {
                const errorMessage = xhr.responseJSON?.message || 'Đã có lỗi xảy ra!';
            }
        });
    }

});
