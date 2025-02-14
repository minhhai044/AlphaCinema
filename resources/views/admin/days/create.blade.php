@extends('admin.layouts.master')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <h1 class="card-title">Tạo mới phim</h1>
            <a href="{{ route('admin.days.index') }}" class="btn btn-primary mb-3">Quay lại</a>
            <form action="{{ route('admin.days.store') }}" method="POST" enctype="multipart/form-data"
                class="custom-validation">
                @csrf
                <div class="row">
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
                    <div class="col-lg-9">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Loại ngày</label>
                                            <input type="text" name="name" class="form-control">
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Phụ phí</label>
                                            <input type="float" name="day_surcharge" class="form-control">
                                            @error('day_surcharge')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">
                            Submit
                        </button>
                        <button type="reset" class="btn btn-secondary waves-effect">
                            Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".edit").forEach(button => {
            button.addEventListener("click", function() {
                let row = this.closest("tr");
                row.querySelectorAll(".day-name, .day-surcharge").forEach(el => el.classList
                    .add("d-none"));
                row.querySelectorAll(".day-input").forEach(el => el.classList.remove("d-none"));
                row.querySelector(".edit").classList.add("d-none");
                row.querySelector(".save").classList.remove("d-none");
            });
        });

        document.querySelectorAll(".save").forEach(button => {
            button.addEventListener("click", function() {
                let row = this.closest("tr");
                let id = row.dataset.id;
                let name = row.querySelectorAll(".day-input")[0].value;
                let surcharge = row.querySelectorAll(".day-input")[1].value;

                fetch(`/admin/days/${id}`, {
                        method: "PUT",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector(
                                'meta[name="csrf-token"]').getAttribute("content"),
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            name: name,
                            day_surcharge: surcharge
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            row.querySelector(".day-name").textContent = name;
                            row.querySelector(".day-surcharge").textContent = parseInt(
                                surcharge).toLocaleString();

                            row.querySelectorAll(".day-name, .day-surcharge").forEach(el =>
                                el.classList.remove("d-none"));
                            row.querySelectorAll(".day-input").forEach(el => el.classList
                                .add("d-none"));
                            row.querySelector(".edit").classList.remove("d-none");
                            row.querySelector(".save").classList.add("d-none");

                            // Đưa bản ghi vừa sửa lên đầu
                            let tbody = row.parentElement;
                            tbody.insertBefore(row, tbody.firstChild);
                        } else {
                            alert("Cập nhật thất bại!");
                        }
                    });
            });
        });
    });
</script>
