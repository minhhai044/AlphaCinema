@extends('admin.layouts.master')
@section('title', 'Quản lý chi nhánh')

@section('style')
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/css/preloader.min.css') }}" />
    <link href="{{ asset('theme/admin/assets/css/icons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('theme/admin/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" />
    <style>
        #datatable_length select {
            width: 60px;
        }

        #datatable thead th {
            text-align: center;
            vertical-align: middle;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <!-- Form thêm mới -->
        <div class="col-lg-4">
            <div class="card">
                <div class="mb-sm-0 font-size-20 fw-semibold ms-3 mt-3">Thêm mới chi nhánh</div>
                <div class="card-body">
                    <form action="{{ route('admin.branches.store') }}" method="post">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label"><span class="text-danger">*</span> Tên chi nhánh:</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                                placeholder="Nhập tên chi nhánh">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label class="form-label"><span class="text-danger">*</span> Phụ phí (VNĐ)</label>
                            <input type="text" value="{{ old('surcharge') ?? 0 }}" id="surchargeCreate" name="surcharge"
                                class="form-control" value="0" placeholder="Nhập phụ phí">
                            @error('surcharge')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="text-end mt-2">
                            <button type="submit" class="btn btn-primary">+ Thêm mới</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Danh sách chi nhánh -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-sm-0 font-size-20 fw-semibold">Danh sách chi nhánh</h4>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên chi nhánh</th>
                                    <th>Phụ phí</th>
                                    <th>Hoạt động</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($branches as $branch)
                                    <tr>
                                        <td>{{ $branch->id }}</td>
                                        <td>{{ $branch->name }}</td>
                                        <td>{{ number_format($branch->surcharge, 0, '.', '.') }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div class="form-check form-switch form-switch-md" dir="ltr">
                                                    <input class="form-check-input switch-is-active changeActive"
                                                        type="checkbox" data-branch-id="{{ $branch->id }}"
                                                        @checked($branch->is_active)>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <button class="btn btn-warning btn-sm editBranch" data-id="{{ $branch->id }}"
                                                data-name="{{ $branch->name }}" data-surcharge="{{ $branch->surcharge }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Phân trang -->
                    {{-- <div class="mt-3">
                        {{ $branches->appends(['search' => request('search')])->links() }}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal chỉnh sửa -->
    <div class="modal fade" id="editBranchModal" tabindex="-1" aria-labelledby="editBranchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editBranchForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Cập nhật chi nhánh</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="form-label">Tên chi nhánh</label>
                            <input type="text" class="form-control" id="branchName" name="branchName">
                            @error('branchName')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Phụ phí</label>
                            <input type="text" class="form-control" id="branchSurcharge" name="branchSurcharge">
                            @error('branchSurcharge')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script src="{{ asset('theme/admin/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

    <script src="{{ asset('theme/admin/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}">
    </script>
    <script src="{{ asset('theme/admin/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}">
    </script>

    <script src="{{ asset('theme/admin/assets/js/pages/datatables.init.js') }}"></script>

    <script>
        $(document).ready(function() {
            @if (session('error_modal') == 'edit' && session('branch_id'))
                const editBtn = $(`.editBranch[data-id="{{ session('branch_id') }}"]`);
                editBtn.trigger('click');
                $('#editBranchModal').modal('show');
                @foreach ($errors->messages() as $field => $messages)
                    let input = $(`[name="{{ $field }}"]`);
                    let errorMessage =
                        `<small class="text-danger">{{ $messages[0] }}</small>`;
                    if (input.length > 0) {
                        input.closest('.mb-2').append(errorMessage);
                    }
                @endforeach
            @endif
        });

        function resetErrors(modalId = '') {
            let modal = modalId ? $(modalId) : $(document);

            modal.find('input, select, textarea').removeClass('is-invalid');

            modal.find('.text-danger').remove();
        }

        $('#surchargeCreate').on('input', function(e) {
            e.preventDefault();
            let valueDiscount = $(this).val().replace(/\D/g, '');
            let setValueDiscount = new Intl.NumberFormat('vi-VN').format(valueDiscount);
            $(this).val(setValueDiscount);
        });
        $('#branchSurcharge').on('input', function(e) {
            e.preventDefault();
            let valueDiscount = $(this).val().replace(/\D/g, '');
            let setValueDiscount = new Intl.NumberFormat('vi-VN').format(valueDiscount);
            $(this).val(setValueDiscount);
        });

        document.querySelectorAll('.editBranch').forEach(button => {
            button.addEventListener('click', function() {

                resetErrors();

                const id = this.dataset.id;
                const name = this.dataset.name;
                const surcharge = this.dataset.surcharge;

                const form = document.getElementById('editBranchForm');
                form.action = `/admin/branches/${id}`;
                document.getElementById('branchName').value = name;

                const formattedSurcharge = new Intl.NumberFormat('vi-VN').format(surcharge.replace(/\D/g,
                    ''));
                document.getElementById('branchSurcharge').value = formattedSurcharge;

                const modal = new bootstrap.Modal(document.getElementById('editBranchModal'));
                modal.show();
            });
        });

        function confirmChange(text = 'Bạn có chắc chắn muốn thay đổi trạng thái chi nhánh?', title =
            'AlphaCinema thông báo') {
            return Swal.fire({
                icon: 'warning',
                title: title,
                text: text,
                showCancelButton: true,
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy',
            }).then(result => result.isConfirmed);
        }

        $(document).on("change", ".changeActive", function(e) {
            e.preventDefault();

            let $checkbox = $(this);
            let branchId = $checkbox.data("branch-id");
            let is_active = $checkbox.is(":checked") ? 1 : 0;

            confirmChange('Bạn có chắc chắn muốn thay đổi trạng thái chi nhánh?').then((confirmed) => {
                if (confirmed) {
                    $.ajax({
                        url: "{{ route('admin.branches.change-active') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: branchId,
                            is_active: is_active
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success('Trạng thái chi nhánh đã được cập nhật.');
                            } else {
                                toastr.error(response.message || 'Có lỗi xảy ra.');
                                $checkbox.prop("checked", !is_active);
                            }
                        },
                        error: function() {
                            toastr.error('Có lỗi xảy ra khi cập nhật trạng thái.');
                            $checkbox.prop("checked", !is_active);
                        }
                    });
                } else {
                    $checkbox.prop("checked", !is_active);
                }
            });
        });
    </script>

@endsection
