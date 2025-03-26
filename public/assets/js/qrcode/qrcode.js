let html5QrCode;
let currentCameraId = null;

function startScanner() {
    html5QrCode = new Html5Qrcode("reader");

    Html5Qrcode.getCameras().then(function (devices) {
        if (devices && devices.length) {
            currentCameraId = devices[0].id;

            html5QrCode.start(
                currentCameraId,
                {
                    fps: 10,
                    qrbox: { width: 300, height: 300 }
                },
                onScanSuccess
            ).catch(function (err) {
                toastr.error('Lỗi khi khởi động camera!');
            });
        } else {
            toastr.error('Không tìm thấy camera nào!');
        }
    }).catch(function (err) {
        toastr.error('Không thể truy cập camera!');
    });
}

function stopScanner() {
    if (html5QrCode) {
        html5QrCode.stop().then(function () {
            html5QrCode.clear();
        }).catch(function (err) {
            toastr.error('Lỗi khi dừng camera!');
        });
    }
}

function onScanSuccess(decodedText, decodedResult) {

    $.ajax({
        url: `/admin/tickets/${decodedText}/check-exists`,
        method: 'GET',
        success: function (response) {
            if (response.exists) {
                toastr.success('Đã quét thành công, đang chuyển hướng...');
                window.location.href = `/admin/tickets/${decodedText}/detail`;
            } else {
                toastr.error('Mã không hợp lệ hoặc không tồn tại!');
                $('#qrModal').modal('hide');
            }
        },
        error: function () {
            toastr.error('Đã xảy ra lỗi khi kiểm tra mã!');
            $('#qrModal').modal('hide');
        }
    });

    html5QrCode.stop().then(function () {
        // $("#scan-again-btn").removeClass('d-none');
    }).catch(function (err) {
        console.error("Lỗi khi dừng camera:", err);
    });
}

// $("#scan-again-btn").on("click", function () {
//     startScanner();
// });

$('#qrModal').on('shown.bs.modal', function () {
    startScanner();
});

$('#qrModal').on('hidden.bs.modal', function () {
    stopScanner();
});
