<meta name="csrf-token" content="{{ csrf_token() }}">
@extends('admin.layouts.master')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0 font-size-18">DAYS</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Days</a></li>
                                        <li class="breadcrumb-item active">Table Days</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <h4 class="card-title">Danh sách phim</h4>

                    <!-- Nút mở modal -->
                    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addDayModal">
                        + Thêm mới
                    </button>
                    <!-- Modal Thêm Ngày Mới -->
                    <div class="modal fade" id="addDayModal" tabindex="-1" aria-labelledby="addDayModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addDayModalLabel">Thêm Ngày Mới</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="addDayForm">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Loại ngày</label>
                                            <input type="text" name="name" id="name" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Phụ phí</label>
                                            <input type="number" name="day_surcharge" id="day_surcharge" class="form-control">
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                    <button type="button" class="btn btn-primary" id="saveDay">Lưu</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                        <thead>

                            <tr>
                                <th></th>
                                <th>Loại ngày</th>
                                <th>Phụ phí </th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($days as $day)
                                <tr data-id="{{ $day->id }}">

                                    <td></td>
                                    <td>
                                        <span class="day-name">{{ $day->name }}</span>
                                        <input type="text" class="form-control day-input d-none" value="{{ $day->name }}">
                                    </td>
                                    <td>
                                        <span class="day-surcharge">{{ number_format($day->day_surcharge) }}</span>
                                        <input type="number" class="form-control day-input d-none"
                                            value="{{ $day->day_surcharge }}">
                                    </td>
                                    <td>
                                        <a class="btn btn-outline-secondary btn-sm edit" title="Edit">
                                            <i class="fas fa-pencil-alt"></i> Sửa
                                        </a>
                                        <a class="btn btn-success btn-sm save d-none" title="Save">
                                            <i class="fas fa-check"></i> Lưu
                                        </a>
                                        <form action="{{ route('admin.days.destroy', $day->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                <i class="fas fa-trash-alt"></i> Xóa
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                <div class="d-flex justify-content-center">
                    {{ $days->links() }}
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection
@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Xử lý chỉnh sửa (Edit)
            document.querySelectorAll(".edit").forEach(button => {
                button.addEventListener("click", function () {
                    let row = this.closest("tr");
                    row.querySelectorAll(".day-name, .day-surcharge").forEach(el => el.classList.add("d-none"));
                    row.querySelectorAll(".day-input").forEach(el => el.classList.remove("d-none"));
                    row.querySelector(".edit").classList.add("d-none");
                    row.querySelector(".save").classList.remove("d-none");
                });
            });

            // Xử lý lưu cập nhật (Save)
            document.querySelectorAll(".save").forEach(button => {
                button.addEventListener("click", function () {
                    let row = this.closest("tr");
                    let id = row.dataset.id;
                    let name = row.querySelector(".day-input[type='text']").value;
                    let day_surcharge = row.querySelector(".day-input[type='number']").value;

                    fetch("/admin/days/update/" + id, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            name: name,
                            day_surcharge: day_surcharge
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                row.querySelector(".day-name").textContent = name;
                                row.querySelector(".day-surcharge").textContent = new Intl.NumberFormat().format(day_surcharge);
                                row.querySelectorAll(".day-name, .day-surcharge").forEach(el => el.classList.remove("d-none"));
                                row.querySelectorAll(".day-input").forEach(el => el.classList.add("d-none"));
                                row.querySelector(".edit").classList.remove("d-none");
                                row.querySelector(".save").classList.add("d-none");
                            } else {
                                alert("Lỗi cập nhật!");
                            }
                        });
                });
            });

            // Xử lý thêm mới (Create new)
            document.getElementById("saveDay").addEventListener("click", function () {
                let name = document.getElementById("name").value;
                let day_surcharge = document.getElementById("day_surcharge").value;

                fetch("{{ route('admin.days.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ name: name, day_surcharge: day_surcharge })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Lấy instance của modal, nếu chưa có thì khởi tạo mới
                            const modalEl = document.getElementById("addDayModal");
                            let modal = bootstrap.Modal.getInstance(modalEl);
                            if (!modal) {
                                modal = new bootstrap.Modal(modalEl);
                            }
                            modal.hide(); // Đóng popup

                            // Thêm dòng mới vào bảng
                            let newRow = `
                        <tr data-id="${data.day.id}">
                            <td></td>
                            <td>
                                <span class="day-name">${data.day.name}</span>
                                <input type="text" class="form-control day-input d-none" value="${data.day.name}">
                            </td>
                            <td>
                                <span class="day-surcharge">${new Intl.NumberFormat().format(data.day.day_surcharge)}</span>
                                <input type="number" class="form-control day-input d-none" value="${data.day.day_surcharge}">
                            </td>
                            <td>
                                <a class="btn btn-outline-secondary btn-sm edit" title="Edit">
                                    <i class="fas fa-pencil-alt"></i> Sửa
                                </a>
                                <a class="btn btn-success btn-sm save d-none" title="Save">
                                    <i class="fas fa-check"></i> Lưu
                                </a>
                                <form action="/admin/days/destroy/${data.day.id}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                        <i class="fas fa-trash-alt"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    `;
                            document.querySelector("tbody").insertAdjacentHTML("beforeend", newRow);

                            // Xóa dữ liệu trong form
                            document.getElementById("name").value = "";
                            document.getElementById("day_surcharge").value = "";

                            alert("Thêm ngày mới thành công!");
                        } else {
                            alert("Lỗi! Vui lòng kiểm tra lại.");
                        }
                    });
            });
        });
    </script>

@endsection

