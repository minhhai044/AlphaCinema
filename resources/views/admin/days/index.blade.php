<meta name="csrf-token" content="{{ csrf_token() }}">
@extends('admin.layouts.master')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Tiêu đề trang -->
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

                    <!-- Nút mở modal thêm mới -->
                    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addDayModal">
                        + Thêm mới
                    </button>

                    <!-- Modal Thêm Ngày Mới -->
                    <div class="modal fade" id="addDayModal" tabindex="-1" aria-labelledby="addDayModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addDayModalLabel">Thêm Ngày Mới</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
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
                                            <input type="number" name="day_surcharge" id="day_surcharge"
                                                class="form-control">
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

                    <!-- Bảng danh sách Days -->
                    <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Loại ngày</th>
                                <th>Phụ phí</th>
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
        document.addEventListener("DOMContentLoaded", () => {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const tbody = document.querySelector("tbody");

            // Khi modal được đóng, hãy loại bỏ focus của bất kỳ phần tử nào bên trong modal
            $('#addDayModal').on('hidden.bs.modal', function () {
                $(this).find(':focus').blur();
            });

            /**
             * Hàm chuyển đổi giữa chế độ xem và chỉnh sửa
             * @param {HTMLElement} row - Dòng cần chuyển đổi
             * @param {boolean} editing - Nếu true bật chế độ chỉnh sửa, false trở lại chế độ xem
             */
            function toggleEditMode(row, editing = true) {
                const spanElements = row.querySelectorAll(".day-name, .day-surcharge");
                const inputElements = row.querySelectorAll(".day-input");
                spanElements.forEach(el => el.classList.toggle("d-none", editing));
                inputElements.forEach(el => el.classList.toggle("d-none", !editing));
                row.querySelector(".edit").classList.toggle("d-none", editing);
                row.querySelector(".save").classList.toggle("d-none", !editing);
            }

            // Xử lý sự kiện Sửa/Lưu bằng event delegation trên tbody
            tbody.addEventListener("click", async (e) => {
                const target = e.target.closest("a");
                if (!target) return;
                const row = target.closest("tr");
                const id = row.dataset.id;

                // Nếu bấm nút Sửa
                if (target.classList.contains("edit")) {
                    toggleEditMode(row, true);
                }
                // Nếu bấm nút Lưu
                else if (target.classList.contains("save")) {
                    const nameInput = row.querySelector(".day-input[type='text']");
                    const surchargeInput = row.querySelector(".day-input[type='number']");
                    const name = nameInput.value;
                    const day_surcharge = surchargeInput.value;

                    try {
                        const response = await fetch(`/admin/days/update/${id}`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json",  // Yêu cầu trả về JSON
                                "X-CSRF-TOKEN": csrfToken
                            },
                            body: JSON.stringify({ name, day_surcharge })
                        });
                        const data = await response.json();
                        if (data.success) {
                            row.querySelector(".day-name").textContent = name;
                            row.querySelector(".day-surcharge").textContent = new Intl.NumberFormat().format(day_surcharge);
                            toggleEditMode(row, false);
                        } else {
                            alert("Lỗi cập nhật!");
                        }
                    } catch (error) {
                        console.error("Error updating day:", error);
                        alert("Có lỗi xảy ra khi cập nhật dữ liệu!");
                    }
                }
            });

            // Xử lý thêm mới (Create new)
            document.getElementById("saveDay").addEventListener("click", async () => {
                const name = document.getElementById("name").value;
                const day_surcharge = document.getElementById("day_surcharge").value;
                try {
                    const response = await fetch("{{ route('admin.days.store') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",  // Yêu cầu trả về JSON
                            "X-CSRF-TOKEN": csrfToken
                        },
                        body: JSON.stringify({ name, day_surcharge })
                    });
                    const data = await response.json();
                    if (data.success) {
                        // Đóng modal sau khi thêm thành công và loại bỏ focus bên trong modal
                        const modalEl = document.getElementById("addDayModal");
                        let modal = bootstrap.Modal.getInstance(modalEl);
                        if (!modal) {
                            modal = new bootstrap.Modal(modalEl);
                        }
                        modal.hide();

                        // Nếu DataTables đã được khởi tạo thì dùng API của DataTables để thêm dòng mới
                        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#datatable')) {
                            const table = $('#datatable').DataTable();
                            const newRowData = [
                                "", // Cột trống
                                `<span class="day-name">${data.day.name}</span>
                                 <input type="text" class="form-control day-input d-none" value="${data.day.name}">`,
                                `<span class="day-surcharge">${new Intl.NumberFormat().format(data.day.day_surcharge)}</span>
                                 <input type="number" class="form-control day-input d-none" value="${data.day.day_surcharge}">`,
                                `<a class="btn btn-outline-secondary btn-sm edit" title="Edit">
                                    <i class="fas fa-pencil-alt"></i> Sửa</a>
                                 <a class="btn btn-success btn-sm save d-none" title="Save">
                                    <i class="fas fa-check"></i> Lưu</a>
                                 <form action="/admin/days/destroy/${data.day.id}" method="POST" class="d-inline">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                        <i class="fas fa-trash-alt"></i> Xóa
                                    </button>
                                 </form>`
                            ];
                            const newRowNode = table.row.add(newRowData).draw().node();
                            $(newRowNode).attr("data-id", data.day.id);
                        } else {
                            // Nếu không sử dụng DataTables, chèn trực tiếp vào tbody
                            const newRow = document.createElement("tr");
                            newRow.setAttribute("data-id", data.day.id);
                            newRow.innerHTML = `
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
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                            <i class="fas fa-trash-alt"></i> Xóa
                                        </button>
                                    </form>
                                </td>
                            `;
                            tbody.insertAdjacentElement("beforeend", newRow);
                        }

                        // Reset dữ liệu form
                        document.getElementById("name").value = "";
                        document.getElementById("day_surcharge").value = "";

                        alert("Thêm ngày mới thành công!");
                    } else {
                        alert("Lỗi! Vui lòng kiểm tra lại.");
                    }
                } catch (error) {
                    console.error("Error creating day:", error);
                    alert("Có lỗi xảy ra khi thêm dữ liệu!");
                }
            });
        });
    </script>
@endsection
