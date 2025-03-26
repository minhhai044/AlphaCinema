$(document).ready(function () {
    let html5QrCode;

    function startScanner() {

        $("#reader").html('');

        html5QrCode = new Html5Qrcode("reader");

        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                let cameraId = devices[0].id;

                html5QrCode.start(cameraId, {
                    fps: 10,
                    qrbox: { width: 400, height: 150 },
                },
                    onScanSuccess
                ).catch(err => {
                    toastr.error('Lỗi khi khởi động camera !!!');

                });
            } else {
                toastr.error('Không tìm thấy camera !!!');
            }
        });
    }


    function stopScanner() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                html5QrCode.clear();
            }).catch(err => console.error(err));
        }
    }



    // function onScanSuccess(decodedText, decodedResult) {
    //     if (html5QrCode) {
    //         html5QrCode.stop().then(() => {
    //             html5QrCode.clear();
    //             html5QrCode = null;

    //             toastr.success('Thao tác thành công !!!');

    //             setTimeout(() => {
    //                 window.location.href = `/admin/tickets/${decodedText}/detail`;
    //             }, 1000); 
    //         }).catch(err => {
    //             console.error(err);
    //             toastr.error('Không thể dừng camera !!!');
    //         });
    //     }
    // }

    function onScanSuccess(decodedText, decodedResult) {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                html5QrCode.clear();
                html5QrCode = null;

                $.ajax({
                    url: `/admin/tickets/${decodedText}/check-exists`,
                    method: 'GET',
                    success: function (response) {
                        if (response.exists) {
                            toastr.success('Đã quét thành công, đang chuyển hướng...');
                            window.location.href = `/admin/tickets/${decodedText}/detail`;
                        } else {
                            toastr.error('Mã không hợp lệ hoặc không tồn tại!');
                            $('#barcodeModal').modal('hide');
                        }
                    },
                    error: function () {
                        toastr.error('Đã xảy ra lỗi khi kiểm tra mã!');
                        $('#barcodeModal').modal('hide');
                    }
                });

            }).catch(err => {
                toastr.error('Không thể dừng camera !!!');
            });
        }
    }

    $('#barcodeModal').on('shown.bs.modal', startScanner);

    $('#barcodeModal').on('hidden.bs.modal', stopScanner);

    $('#barcode-file').change(function () {
        const file = this.files[0];
        if (file) {
            const html5QrCodeFile = new Html5Qrcode("reader");
            html5QrCodeFile.scanFile(file, true)
                .then(decodedText => {
                    $.ajax({
                        url: `/admin/tickets/${decodedText}/check-exists`,
                        method: 'GET',
                        success: function (response) {
                            if (response.exists) {
                                toastr.success('Đã quét thành công, đang chuyển hướng...');
                                window.location.href = `/admin/tickets/${decodedText}/detail`;
                            } else {
                                toastr.error('Mã không hợp lệ hoặc không tồn tại!');
                                $('#barcodeModal').modal('hide');
                            }
                        },
                        error: function () {
                            toastr.error('Đã xảy ra lỗi khi kiểm tra mã!');
                            $('#barcodeModal').modal('hide');
                        }
                    });
                })
                .catch(err => {
                    toastr.error('Mã không hợp lệ hoặc không tồn tại!');
                });
        }
    });

});
