<meta name="csrf-token" content="{{ csrf_token() }}">
@extends('admin.layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0 font-size-18">Quản lí Ngày</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item active"><a href="javascript: void(0);">Danh sách ngày</a></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4 class="card-title">Danh sách ngày</h4>

                    <table id="datatable" class="table align-middle dt-responsive nowrap w-100 table-bordered text-center">
                        <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center">Loại ngày</th>
                                <th class="text-center">Phụ phí</th>
                                <th class="text-center">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($days as $day)
                                <tr data-id="{{ $day->id }}">
                                    <td class="text-center">{{ $day->id }}</td>
                                    <td class="text-center">{{ $day->name }}</td>
                                    <td class="text-center">
                                        <span class="day-surcharge">{{ number_format($day->day_surcharge) }} VNĐ</span>
                                        <input type="number" class="form-control day-input d-none" value="{{ $day->day_surcharge }}">
                                        <div class="text-danger error-message d-none"></div>
                                    </td>
                                    <td class="text-center">
                                        <a class="btn  btn-sm edit btn btn-warning" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <a class="btn btn-success btn-sm save d-none" title="Save">
                                            <i class="fas fa-check"></i> Lưu
                                        </a>
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
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const tbody = document.querySelector("tbody");

            function toggleEditMode(row, editing = true) {
                row.querySelector(".day-surcharge").classList.toggle("d-none", editing);
                row.querySelector(".day-input").classList.toggle("d-none", !editing);
                row.querySelector(".edit").classList.toggle("d-none", editing);
                row.querySelector(".save").classList.toggle("d-none", !editing);
            }

            tbody.addEventListener("click", async (e) => {
                const target = e.target.closest("a");
                if (!target) return;

                const row = target.closest("tr");
                const id = row.dataset.id;

                if (target.classList.contains("edit")) {
                    toggleEditMode(row, true);
                } else if (target.classList.contains("save")) {
                    const surchargeInput = row.querySelector(".day-input");
                    const errorDiv = row.querySelector(".error-message");
                    const day_surcharge = parseInt(surchargeInput.value);

                    if (isNaN(day_surcharge) || day_surcharge < 0) {
                        if (errorDiv) {
                            errorDiv.textContent = "Phụ phí phải là số nguyên dương.";
                            errorDiv.classList.remove("d-none");
                        }
                        return;
                    }

                    try {
                        const response = await fetch(`/admin/days/update/${id}`, {
                            method: "PUT",
                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json",
                                "X-CSRF-TOKEN": csrfToken
                            },
                            body: JSON.stringify({ day_surcharge })
                        });

                        if (response.ok) {
                            const data = await response.json();
                            if (data.success) {
                                row.querySelector(".day-surcharge").textContent = new Intl.NumberFormat().format(data.data.day_surcharge) + " VNĐ";
                                toggleEditMode(row, false);

                                if (errorDiv) {
                                    errorDiv.textContent = "";
                                    errorDiv.classList.add("d-none");
                                }

                                toastr.success("Cập nhật thành công!", "Thành công");
                            } else {
                                toastr.error("Lỗi cập nhật, vui lòng thử lại!", "Lỗi");
                            }
                        } else if (response.status === 422) {
                            const errorData = await response.json();
                            if (errorData.errors && errorData.errors.day_surcharge) {
                                if (errorDiv) {
                                    errorDiv.textContent = errorData.errors.day_surcharge[0];
                                    errorDiv.classList.remove("d-none");
                                }
                            }
                            toastr.error("Dữ liệu không hợp lệ!", "Lỗi");
                        } else {
                            toastr.error("Có lỗi xảy ra khi cập nhật!", "Lỗi");
                        }
                    } catch (error) {
                        console.error("Error updating day:", error);
                        toastr.error("Có lỗi xảy ra khi cập nhật!", "Lỗi");
                    }
                }
            });
        });
    </script>
@endsection
