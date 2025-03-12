$(document).ready(function () {
    // Hàm định dạng tiền tệ
    const formatCurrency = (number) => {
        return new Intl.NumberFormat('vi-VN', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(number);
    };


    // Xử lý in vé
    $(document).on("click", ".printTicket", async function () {
        var ticketID = $(this).data("id");

        try {
            let response = await $.ajax({
                url: `/api/v1/tickets/${ticketID}`,
                type: "GET"
            });

            if (!response.data) throw new Error('Dữ liệu vé không tồn tại.');

            const data = response.data;
            $("#ticketContainer").empty();

            const seatCount = data.ticket_seats ? data.ticket_seats.length : 0;
            const pricePerSeat = seatCount > 0 ? (data.ticket.total_price / seatCount) : (data
                .ticket.total_price || 0);

            if (data.ticket_seats && data.ticket_seats.length > 0) {
                data.ticket_seats.forEach((seat, index) => {
                    const ticketHtml = `
                            <div class="ticket-item mb-3 ${index > 0 ? 'mt-4' : ''}">
                                <h3 class="text-center fw-bold">Chi tiết vé xem phim</h3>
                                <div class="mb-1">
                                    <div class="mb-1 fs-5 fw-bold">Chi nhánh công ty Alpha Cinema tại ${data.branch || 'N/A'}</div>
                                    <div class="mb-1 fw-semibold">MST: 012147901412</div>
                                </div>
                                <hr class="dashed-hr">
                                <div class="mb-1">
                                    <h5 class="fw-bold">Thông tin rạp</h5>
                                    <div class="mb-1"><strong>Rạp chiếu:</strong> ${data.cinema || 'N/A'}</div>
                                    <div class="mb-1"><strong>Địa chỉ:</strong> ${data.address || 'N/A'}</div>
                                    <div class="mb-1"><strong>Thời gian:</strong> (${data.start_time || 'N/A'} - ${data.end_time || 'N/A'}) -- ${data.showtime || 'N/A'}</div>
                                    <div class="fw-semibold">Nhân viên in vé:  Đỗ Nam Trung </div>
                                </div>
                                <hr class="dashed-hr">
                                <div class="mb-1">
                                    <h5 class="fw-bold">Thông tin phim</h5>
                                    <div class="mb-1"><strong>Phim:</strong> ${data.movie || 'N/A'}</div>
                                    <div class="mb-1"><strong>Thể loại:</strong> ${data.category_movie || 'N/A'} - ${data.type_movie || 'N/A'}</div>
                                    <div class="mb-1"><strong>Thời lượng:</strong> ${data.duration || 'N/A'} phút</div>
                                </div>
                                <hr class="dashed-hr">
                                <div class="mb-1">
                                    <div class="mb-1"><strong>Phòng:</strong> ${data.room || 'N/A'}</div>
                                    <div class="mb-1"><strong>Ghế:</strong> ${seat.seat_name || 'N/A'}</div>
                                </div>
                                <hr class="dashed-hr">
                                <div class="mb-1">
                                    <h5 class="fw-bold">Thanh toán</h5>
                                    <div class="mb-1"><strong>Phương thức thanh toán:</strong> ${data.ticket?.payment_name || 'N/A'}</div>
                                    <div class="mb-1"><strong>Giá vé:</strong> ${formatCurrency(pricePerSeat)} VND</div>
                                </div>


                                <div class="my-5 d-flex flex-column align-items-center text-center">
                                    <div> ${data.barcode}</div>
                                    <div class="fw-bold fs-5 text-dark">893 ${data.code}</div>
                                </div>

                                    <div class="mt-5 d-flex justify-content-center align-items-center text-center">
                                    <div> Alpha Cinema - Cảm ơn bạn đã sử dụng dịch vụ! </div>
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
        var ticketID = $(this).data("id");

        try {
            let response = await $.ajax({
                url: `/api/v1/tickets/combo/${ticketID}`,
                type: "GET"
            });

            if (!response.data) throw new Error('Dữ liệu combo không tồn tại.');

            const data = response.data;
            $("#ticketContainer").empty();

            let totalComboPrice = 0;
            let totalFoodPrice = 0;
            let discount = 0;
            let totalPrice = 0;

            if (data.ticket_combos && data.ticket_combos.length > 0) {
                data.ticket_combos.forEach(combo => {
                    totalComboPrice += (combo.price || 0) * (combo.quantity || 0);
                    if (combo.foods && combo.foods.length > 0) {
                        combo.foods.forEach(item => {
                            totalFoodPrice += (item.price || 0) * (item
                                .quantity || 0);
                        });
                    }
                });
            }

            if (data.ticket_foods && data.ticket_foods.length > 0) {
                data.ticket_foods.forEach(food => {
                    totalFoodPrice += (food.price || 0) * (food.quantity || 0);
                });
            }

            discount = (totalComboPrice + totalFoodPrice) * 0.1;
            totalPrice = totalComboPrice + totalFoodPrice - discount;

            const ticketHtml = `
                    <div class="ticket-item mb-3">
                        <h3 class="text-center fw-bold">Hóa Đơn Đồ Ăn</h3>
                        <div class="mb-1">
                            <div class="mb-1 fs-5 fw-bold">Chi nhánh công ty Alpha Cinema tại ${data.branch || 'N/A'}</div>
                            <div class="mb-1 fw-semibold">MST: 012147901412</div>
                        </div>
                        <hr class="dashed-hr">
                        <div class="mb-1">
                            <div class="mb-1 fw-semibold fs-5">Alpha Cinema ${data.cinema || 'N/A'} - ${data.branch || 'N/A'}</div>
                            <div class="mb-1">Thời gian đặt vé: ${data.created_at || 'N/A'}</div>
                            <p>Được in bởi: Donald Trump</p>
                        </div>
                        <hr class="dashed-hr">
                        ${data.ticket_combos && data.ticket_combos.length > 0 ? data.ticket_combos.map(combo => `
                                                        <div class="mb-1 fw-semibold">${combo.name || 'N/A'} x ${combo.quantity || 0} (${formatCurrency((combo.price || 0) * (combo.quantity || 0))} VND)</div>
                                                        <ul>
                                                            ${combo.foods && combo.foods.length > 0 ? combo.foods.map(item => `
                                    <li>${item.name || 'N/A'} x ${item.quantity || 0} (${formatCurrency((item.price || 0) * (item.quantity || 0))} VND)</li>
                                `).join('') : '<li>Không có món ăn</li>'}
                                                        </ul>
                                                    `).join('') : '<p>Không có combo nào.</p>'}
                        ${data.ticket_foods && data.ticket_foods.length > 0 ? data.ticket_foods.map(food => `
                                                        <div class="mb-1 fw-semibold">${food.name || 'N/A'} x ${food.quantity || 0} (${formatCurrency((food.price || 0) * (food.quantity || 0))} VND)</div>
                                                    `).join('') : '<p>Không có món ăn riêng lẻ.</p>'}
                        <hr class="dashed-hr">
                        <div class="mb-1">
                            <div class="mb-1 d-flex justify-content-between">
                                <div class="fw-semibold">Tổng cộng:</div>
                                <div class="ms-2 fw-semibold">${formatCurrency(totalComboPrice + totalFoodPrice)} VND</div>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <div class="fw-semibold">Giảm giá:</div>
                                <div class="ms-2 fw-semibold">${formatCurrency(discount)} VND</div>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <div class="fw-semibold">Thành tiền:</div>
                                <div class="fw-semibold">${formatCurrency(totalPrice)} VND</div>
                            </div>
                        </div>

                         <div class="my-5 d-flex flex-column align-items-center text-center">
                            <div> ${data.barcode}</div>
                            <div class="fw-bold fs-5 text-dark">893 ${data.code}</div>
                        </div>

                        <div class="mt-5 d-flex justify-content-center align-items-center text-center">
                            <div> Alpha Cinema - Cảm ơn bạn đã sử dụng dịch vụ! </div>
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
                                background-color: #efefff;
                            }
                            /* Các thành phần khác của bảng */
                            body {
                                font-family: Arial, sans-serif;
                            }
                            .ticket-item {
                                padding: 15px;
                                margin-bottom: 20px;
                            }
                        }
                        /* Định dạng thông tin thêm nếu cần */
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

            // Chờ hộp thoại in đóng
            setTimeout(function () {
                window.location.reload();
            }, 100);
        }, 50);
    });

});
