@extends('admin.layouts.master')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <!-- Thêm container chứa tiêu đề và dropdown -->
                <div class="d-flex align-items-center">
                    <h4 class="mb-sm-0 font-size-18 me-3">Thống kê trên toàn hệ thống</h4>

                    <!-- Dropdown lọc chi nhánh -->
                    <select id="branchSelect" class="form-select form-select-sm w-auto">
                        <option value="all" selected>Tất cả</option>
                        <option value="hanoi">Hà Nội</option>
                        <option value="haiphong">Hải Phòng</option>
                        <option value="danang">Đà Nẵng</option>
                        <option value="hochiminh">Hồ Chí Minh</option>
                    </select>
                </div>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Thống kê</a></li>
                        <li class="breadcrumb-item active">Thống kê</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-h-100">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Doanh thu</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="">0</span>VND
                            </h4>
                        </div>

                        <div class="col-6">
                            <div id="mini-chart1" data-colors='["#5156be"]' class="apex-charts mb-2">
                            </div>
                        </div>
                    </div>
                    <div class="text-nowrap">
                        {{-- Nếu giảm thì dùng cái text-danger --}}
                        <span class="badge bg-danger-subtle text-danger"><i class="bi bi-arrow-up"></i> 0%</span>
                        {{-- Nếu tăng thì dùng cái text-success --}}
                        <span class="badge bg-success-subtle text-success"><i class="bi bi-arrow-up"></i> 0%</span>

                        <span class="ms-1 text-muted font-size-13">So với tháng trước</span>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-h-100">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Số vé bán ra</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target=""></span> VÉ
                            </h4>
                        </div>
                        <div class="col-6">
                            <div id="mini-chart2" data-colors='["#5156be"]' class="apex-charts mb-2">
                            </div>
                        </div>
                    </div>
                    <div class="text-nowrap">
                        {{-- Nếu giảm thì dùng cái text-danger --}}
                        <span class="badge bg-danger-subtle text-danger"><i class="bi bi-arrow-up"></i> 0%</span>
                        {{-- Nếu tăng thì dùng cái text-success --}}
                        <span class="badge bg-success-subtle text-success"><i class="bi bi-arrow-up"></i> 0%</span>

                        <span class="ms-1 text-muted font-size-13">So với tháng trước</span>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col-->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-h-100">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Khách hàng mới</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="">0</span>
                            </h4>
                        </div>
                        <div class="col-6">
                            <div id="mini-chart3" data-colors='["#5156be"]' class="apex-charts mb-2">
                            </div>
                        </div>
                    </div>
                    <div class="text-nowrap">
                        {{-- Nếu giảm thì dùng cái text-danger --}}
                        <span class="badge bg-danger-subtle text-danger"><i class="bi bi-arrow-up"></i> 0%</span>
                        {{-- Nếu tăng thì dùng cái text-success --}}
                        <span class="badge bg-success-subtle text-success"><i class="bi bi-arrow-up"></i> 0%</span>
                        <span class="ms-1 text-muted font-size-13">So với tháng trước</span>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-h-100">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Suất chiếu</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="">0</span>
                            </h4>
                        </div>
                        <div class="col-6">
                            <div id="mini-chart4" data-colors='["#5156be"]' class="apex-charts mb-2">
                            </div>
                        </div>
                    </div>
                    <div class="text-nowrap">
                        {{-- Nếu giảm thì dùng cái text-danger --}}
                        <span class="badge bg-danger-subtle text-danger"><i class="bi bi-arrow-up"></i> 0%</span>
                        {{-- Nếu tăng thì dùng cái text-success --}}
                        <span class="badge bg-success-subtle text-success"><i class="bi bi-arrow-up"></i> 0%</span>
                        <span class="ms-1 text-muted font-size-13">So với tháng trước</span>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row-->

    <div class="row">
        <div class="col-xl-4">
            <div class="card card-h-100">
                <div class="card-body">
                    <h5 class="card-title">Tỷ lệ đặt ghế</h5>
                    <div id="seat-booking-chart"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card card-h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title">Doanh thu</h5>
                        <select id="yearSelect" class="form-select w-auto">
                            <option value="2025" selected>Năm 2025</option>
                            <option value="2024">Năm 2024</option>
                        </select>
                    </div>
                    <div id="revenue-chart"></div>
                </div>
            </div>
        </div>
    </div> <!-- end row-->

    

    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Trading</h4>
                    <div class="flex-shrink-0">
                        <ul class="nav nav-tabs-custom card-header-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="index.html#buy-tab"
                                    role="tab">Buy</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="index.html#sell-tab"
                                    role="tab">Sell</a>
                            </li>
                        </ul>
                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="buy-tab" role="tabpanel">
                            <div class="float-end ms-2">
                                <h5 class="font-size-14"><i
                                        class="bx bx-wallet text-primary font-size-16 align-middle me-1"></i>
                                    <a href="index.html#!" class="text-reset text-decoration-underline">$4335.23</a>
                                </h5>
                            </div>
                            <h5 class="font-size-14 mb-4">Buy Coins</h5>
                            <div>
                                <div class="form-group mb-3">
                                    <label>Payment method :</label>
                                    <select class="form-select">
                                        <option>Direct Bank Payment</option>
                                        <option>Credit / Debit Card</option>
                                        <option>Paypal</option>
                                        <option>Payoneer</option>
                                        <option>Stripe</option>
                                    </select>
                                </div>

                                <div>
                                    <label>Add Amount :</label>
                                    <div class="input-group mb-3">
                                        <label class="input-group-text">Amount</label>
                                        <select class="form-select" style="max-width: 90px;">
                                            <option value="BT" selected>BTC</option>
                                            <option value="ET">ETH</option>
                                            <option value="LT">LTC</option>
                                        </select>
                                        <input type="text" class="form-control" placeholder="0.00121255">
                                    </div>

                                    <div class="input-group mb-3">
                                        <label class="input-group-text">Price</label>
                                        <input type="text" class="form-control" placeholder="$58,245">
                                        <label class="input-group-text">$</label>
                                    </div>

                                    <div class="input-group mb-3">
                                        <label class="input-group-text">Total</label>
                                        <input type="text" class="form-control" placeholder="$36,854.25">
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="button" class="btn btn-success w-md">Buy Coin</button>
                                </div>
                            </div>
                        </div>
                        <!-- end tab pane -->
                        <div class="tab-pane" id="sell-tab" role="tabpanel">
                            <div class="float-end ms-2">
                                <h5 class="font-size-14"><i
                                        class="bx bx-wallet text-primary font-size-16 align-middle me-1"></i>
                                    <a href="index.html#!" class="text-reset text-decoration-underline">$4235.23</a>
                                </h5>
                            </div>
                            <h5 class="font-size-14 mb-4">Sell Coins</h5>

                            <div>

                                <div class="form-group mb-3">
                                    <label>Wallet ID :</label>
                                    <input type="email" class="form-control" placeholder="1cvb254ugxvfcd280ki">
                                </div>

                                <div>
                                    <label>Add Amount :</label>
                                    <div class="input-group mb-3">
                                        <label class="input-group-text">Amount</label>

                                        <select class="form-select" style="max-width: 90px;">
                                            <option value="BT" selected>BTC</option>
                                            <option value="ET">ETH</option>
                                            <option value="LT">LTC</option>
                                        </select>
                                        <input type="text" class="form-control" placeholder="0.00121255">
                                    </div>

                                    <div class="input-group mb-3">

                                        <label class="input-group-text">Price</label>

                                        <input type="text" class="form-control" placeholder="$23,754.25">

                                        <label class="input-group-text">$</label>
                                    </div>

                                    <div class="input-group mb-3">
                                        <label class="input-group-text">Total</label>
                                        <input type="text" class="form-control" placeholder="$6,852.41">
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="button" class="btn btn-danger w-md">Sell Coin</button>
                                </div>
                            </div>
                        </div>
                        <!-- end tab pane -->
                    </div>
                    <!-- end tab content -->
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->

        <div class="col-xl-4">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Transactions</h4>
                    <div class="flex-shrink-0">
                        <ul class="nav justify-content-end nav-tabs-custom rounded card-header-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="index.html#transactions-all-tab"
                                    role="tab">
                                    All
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="index.html#transactions-buy-tab"
                                    role="tab">
                                    Buy
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="index.html#transactions-sell-tab"
                                    role="tab">
                                    Sell
                                </a>
                            </li>
                        </ul>
                        <!-- end nav tabs -->
                    </div>
                </div><!-- end card header -->

                <div class="card-body px-0">
                    <div class="tab-content">
                        <div class="tab-pane active" id="transactions-all-tab" role="tabpanel">
                            <div class="table-responsive px-3" data-simplebar style="max-height: 352px;">
                                <table class="table align-middle table-nowrap table-borderless">
                                    <tbody>
                                        <tr>
                                            <td style="width: 50px;">
                                                <div class="font-size-22 text-success">
                                                    <i class="bx bx-down-arrow-circle d-block"></i>
                                                </div>
                                            </td>

                                            <td>
                                                <div>
                                                    <h5 class="font-size-14 mb-1">Buy BTC</h5>
                                                    <p class="text-muted mb-0 font-size-12">14 Mar, 2021
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 mb-0">0.016 BTC</h5>
                                                    <p class="text-muted mb-0 font-size-12">Coin Value
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 text-muted mb-0">$125.20
                                                    </h5>
                                                    <p class="text-muted mb-0 font-size-12">Amount</p>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="font-size-22 text-danger">
                                                    <i class="bx bx-up-arrow-circle d-block"></i>
                                                </div>
                                            </td>

                                            <td>
                                                <div>
                                                    <h5 class="font-size-14 mb-1">Sell ETH</h5>
                                                    <p class="text-muted mb-0 font-size-12">15 Mar, 2021
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 mb-0">0.56 ETH</h5>
                                                    <p class="text-muted mb-0 font-size-12">Coin Value
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 text-muted mb-0">$112.34
                                                    </h5>
                                                    <p class="text-muted mb-0 font-size-12">Amount</p>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="font-size-22 text-success">
                                                    <i class="bx bx-down-arrow-circle d-block"></i>
                                                </div>
                                            </td>

                                            <td>
                                                <div>
                                                    <h5 class="font-size-14 mb-1">Buy LTC</h5>
                                                    <p class="text-muted mb-0 font-size-12">16 Mar, 2021
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 mb-0">1.88 LTC</h5>
                                                    <p class="text-muted mb-0 font-size-12">Coin Value
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 text-muted mb-0">$94.22</h5>
                                                    <p class="text-muted mb-0 font-size-12">Amount</p>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="font-size-22 text-success">
                                                    <i class="bx bx-down-arrow-circle d-block"></i>
                                                </div>
                                            </td>

                                            <td>
                                                <div>
                                                    <h5 class="font-size-14 mb-1">Buy ETH</h5>
                                                    <p class="text-muted mb-0 font-size-12">17 Mar, 2021
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 mb-0">0.42 ETH</h5>
                                                    <p class="text-muted mb-0 font-size-12">Coin Value
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 text-muted mb-0">$84.32</h5>
                                                    <p class="text-muted mb-0 font-size-12">Amount</p>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="font-size-22 text-danger">
                                                    <i class="bx bx-up-arrow-circle d-block"></i>
                                                </div>
                                            </td>

                                            <td>
                                                <div>
                                                    <h5 class="font-size-14 mb-1">Sell BTC</h5>
                                                    <p class="text-muted mb-0 font-size-12">18 Mar, 2021
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 mb-0">0.018 BTC</h5>
                                                    <p class="text-muted mb-0 font-size-12">Coin Value
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 text-muted mb-0">$145.80
                                                    </h5>
                                                    <p class="text-muted mb-0 font-size-12">Amount</p>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="width: 50px;">
                                                <div class="font-size-22 text-success">
                                                    <i class="bx bx-down-arrow-circle d-block"></i>
                                                </div>
                                            </td>

                                            <td>
                                                <div>
                                                    <h5 class="font-size-14 mb-1">Buy BTC</h5>
                                                    <p class="text-muted mb-0 font-size-12">14 Mar, 2021
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 mb-0">0.016 BTC</h5>
                                                    <p class="text-muted mb-0 font-size-12">Coin Value
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 text-muted mb-0">$125.20
                                                    </h5>
                                                    <p class="text-muted mb-0 font-size-12">Amount</p>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="font-size-22 text-danger">
                                                    <i class="bx bx-up-arrow-circle d-block"></i>
                                                </div>
                                            </td>

                                            <td>
                                                <div>
                                                    <h5 class="font-size-14 mb-1">Sell ETH</h5>
                                                    <p class="text-muted mb-0 font-size-12">15 Mar, 2021
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 mb-0">0.56 ETH</h5>
                                                    <p class="text-muted mb-0 font-size-12">Coin Value
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 text-muted mb-0">$112.34
                                                    </h5>
                                                    <p class="text-muted mb-0 font-size-12">Amount</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- end tab pane -->
                        <div class="tab-pane" id="transactions-buy-tab" role="tabpanel">
                            <div class="table-responsive px-3" data-simplebar style="max-height: 352px;">
                                <table class="table align-middle table-nowrap table-borderless">
                                    <tbody>
                                        <tr>
                                            <td style="width: 50px;">
                                                <div class="font-size-22 text-success">
                                                    <i class="bx bx-down-arrow-circle d-block"></i>
                                                </div>
                                            </td>

                                            <td>
                                                <div>
                                                    <h5 class="font-size-14 mb-1">Buy BTC</h5>
                                                    <p class="text-muted mb-0 font-size-12">14 Mar, 2021
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 mb-0">0.016 BTC</h5>
                                                    <p class="text-muted mb-0 font-size-12">Coin Value
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 text-muted mb-0">$125.20
                                                    </h5>
                                                    <p class="text-muted mb-0 font-size-12">Amount</p>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="font-size-22 text-success">
                                                    <i class="bx bx-down-arrow-circle d-block"></i>
                                                </div>
                                            </td>

                                            <td>
                                                <div>
                                                    <h5 class="font-size-14 mb-1">Buy BTC</h5>
                                                    <p class="text-muted mb-0 font-size-12">18 Mar, 2021
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 mb-0">0.018 BTC</h5>
                                                    <p class="text-muted mb-0 font-size-12">Coin Value
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 text-muted mb-0">$145.80
                                                    </h5>
                                                    <p class="text-muted mb-0 font-size-12">Amount</p>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="font-size-22 text-success">
                                                    <i class="bx bx-down-arrow-circle d-block"></i>
                                                </div>
                                            </td>

                                            <td>
                                                <div>
                                                    <h5 class="font-size-14 mb-1">Buy LTC</h5>
                                                    <p class="text-muted mb-0 font-size-12">16 Mar, 2021
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 mb-0">1.88 LTC</h5>
                                                    <p class="text-muted mb-0 font-size-12">Coin Value
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 text-muted mb-0">$94.22</h5>
                                                    <p class="text-muted mb-0 font-size-12">Amount</p>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="font-size-22 text-success">
                                                    <i class="bx bx-down-arrow-circle d-block"></i>
                                                </div>
                                            </td>

                                            <td>
                                                <div>
                                                    <h5 class="font-size-14 mb-1">Buy ETH</h5>
                                                    <p class="text-muted mb-0 font-size-12">15 Mar, 2021
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 mb-0">0.56 ETH</h5>
                                                    <p class="text-muted mb-0 font-size-12">Coin Value
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 text-muted mb-0">$112.34
                                                    </h5>
                                                    <p class="text-muted mb-0 font-size-12">Amount</p>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="font-size-22 text-success">
                                                    <i class="bx bx-down-arrow-circle d-block"></i>
                                                </div>
                                            </td>

                                            <td>
                                                <div>
                                                    <h5 class="font-size-14 mb-1">Buy ETH</h5>
                                                    <p class="text-muted mb-0 font-size-12">17 Mar, 2021
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 mb-0">0.42 ETH</h5>
                                                    <p class="text-muted mb-0 font-size-12">Coin Value
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 text-muted mb-0">$84.32</h5>
                                                    <p class="text-muted mb-0 font-size-12">Amount</p>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="font-size-22 text-success">
                                                    <i class="bx bx-down-arrow-circle d-block"></i>
                                                </div>
                                            </td>

                                            <td>
                                                <div>
                                                    <h5 class="font-size-14 mb-1">Buy ETH</h5>
                                                    <p class="text-muted mb-0 font-size-12">15 Mar, 2021
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 mb-0">0.56 ETH</h5>
                                                    <p class="text-muted mb-0 font-size-12">Coin Value
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 text-muted mb-0">$112.34
                                                    </h5>
                                                    <p class="text-muted mb-0 font-size-12">Amount</p>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="width: 50px;">
                                                <div class="font-size-22 text-success">
                                                    <i class="bx bx-down-arrow-circle d-block"></i>
                                                </div>
                                            </td>

                                            <td>
                                                <div>
                                                    <h5 class="font-size-14 mb-1">Buy BTC</h5>
                                                    <p class="text-muted mb-0 font-size-12">14 Mar, 2021
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 mb-0">0.016 BTC</h5>
                                                    <p class="text-muted mb-0 font-size-12">Coin Value
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 text-muted mb-0">$125.20
                                                    </h5>
                                                    <p class="text-muted mb-0 font-size-12">Amount</p>
                                                </div>
                                            </td>
                                        </tr>


                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- end tab pane -->
                        <div class="tab-pane" id="transactions-sell-tab" role="tabpanel">
                            <div class="table-responsive px-3" data-simplebar style="max-height: 352px;">
                                <table class="table align-middle table-nowrap table-borderless">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="font-size-22 text-danger">
                                                    <i class="bx bx-up-arrow-circle d-block"></i>
                                                </div>
                                            </td>

                                            <td>
                                                <div>
                                                    <h5 class="font-size-14 mb-1">Sell ETH</h5>
                                                    <p class="text-muted mb-0 font-size-12">15 Mar, 2021
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 mb-0">0.56 ETH</h5>
                                                    <p class="text-muted mb-0 font-size-12">Coin Value
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 text-muted mb-0">$112.34
                                                    </h5>
                                                    <p class="text-muted mb-0 font-size-12">Amount</p>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="width: 50px;">
                                                <div class="font-size-22 text-danger">
                                                    <i class="bx bx-up-arrow-circle d-block"></i>
                                                </div>
                                            </td>

                                            <td>
                                                <div>
                                                    <h5 class="font-size-14 mb-1">Sell BTC</h5>
                                                    <p class="text-muted mb-0 font-size-12">14 Mar, 2021
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 mb-0">0.016 BTC</h5>
                                                    <p class="text-muted mb-0 font-size-12">Coin Value
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 text-muted mb-0">$125.20
                                                    </h5>
                                                    <p class="text-muted mb-0 font-size-12">Amount</p>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="font-size-22 text-danger">
                                                    <i class="bx bx-up-arrow-circle d-block"></i>
                                                </div>
                                            </td>

                                            <td>
                                                <div>
                                                    <h5 class="font-size-14 mb-1">Sell BTC</h5>
                                                    <p class="text-muted mb-0 font-size-12">18 Mar, 2021
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 mb-0">0.018 BTC</h5>
                                                    <p class="text-muted mb-0 font-size-12">Coin Value
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 text-muted mb-0">$145.80
                                                    </h5>
                                                    <p class="text-muted mb-0 font-size-12">Amount</p>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="font-size-22 text-danger">
                                                    <i class="bx bx-up-arrow-circle d-block"></i>
                                                </div>
                                            </td>

                                            <td>
                                                <div>
                                                    <h5 class="font-size-14 mb-1">Sell ETH</h5>
                                                    <p class="text-muted mb-0 font-size-12">15 Mar, 2021
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 mb-0">0.56 ETH</h5>
                                                    <p class="text-muted mb-0 font-size-12">Coin Value
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 text-muted mb-0">$112.34
                                                    </h5>
                                                    <p class="text-muted mb-0 font-size-12">Amount</p>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="font-size-22 text-danger">
                                                    <i class="bx bx-up-arrow-circle d-block"></i>
                                                </div>
                                            </td>

                                            <td>
                                                <div>
                                                    <h5 class="font-size-14 mb-1">Sell LTC</h5>
                                                    <p class="text-muted mb-0 font-size-12">16 Mar, 2021
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 mb-0">1.88 LTC</h5>
                                                    <p class="text-muted mb-0 font-size-12">Coin Value
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 text-muted mb-0">$94.22</h5>
                                                    <p class="text-muted mb-0 font-size-12">Amount</p>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="font-size-22 text-danger">
                                                    <i class="bx bx-up-arrow-circle d-block"></i>
                                                </div>
                                            </td>

                                            <td>
                                                <div>
                                                    <h5 class="font-size-14 mb-1">Sell ETH</h5>
                                                    <p class="text-muted mb-0 font-size-12">17 Mar, 2021
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 mb-0">0.42 ETH</h5>
                                                    <p class="text-muted mb-0 font-size-12">Coin Value
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 text-muted mb-0">$84.32</h5>
                                                    <p class="text-muted mb-0 font-size-12">Amount</p>
                                                </div>
                                            </td>
                                        </tr>



                                        <tr>
                                            <td style="width: 50px;">
                                                <div class="font-size-22 text-danger">
                                                    <i class="bx bx-up-arrow-circle d-block"></i>
                                                </div>
                                            </td>

                                            <td>
                                                <div>
                                                    <h5 class="font-size-14 mb-1">Sell BTC</h5>
                                                    <p class="text-muted mb-0 font-size-12">14 Mar, 2021
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 mb-0">0.016 BTC</h5>
                                                    <p class="text-muted mb-0 font-size-12">Coin Value
                                                    </p>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="text-end">
                                                    <h5 class="font-size-14 text-muted mb-0">$125.20
                                                    </h5>
                                                    <p class="text-muted mb-0 font-size-12">Amount</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- end tab pane -->
                    </div>
                    <!-- end tab content -->
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->

        <div class="col-xl-4">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Recent Activity</h4>
                    <div class="flex-shrink-0">
                        <select class="form-select form-select-sm mb-0 my-n1">
                            <option value="Today" selected="">Today</option>
                            <option value="Yesterday">Yesterday</option>
                            <option value="Week">Last Week</option>
                            <option value="Month">Last Month</option>
                        </select>
                    </div>
                </div><!-- end card header -->

                <div class="card-body px-0">
                    <div class="px-3" data-simplebar style="max-height: 352px;">
                        <ul class="list-unstyled activity-wid mb-0">

                            <li class="activity-list activity-border">
                                <div class="activity-icon avatar-md">
                                    <span class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                        <i class="bx bx-bitcoin font-size-24"></i>
                                    </span>
                                </div>
                                <div class="timeline-list-item">
                                    <div class="d-flex">
                                        <div class="flex-grow-1 overflow-hidden me-4">
                                            <h5 class="font-size-14 mb-1">24/05/2021, 18:24:56</h5>
                                            <p class="text-truncate text-muted font-size-13">
                                                0xb77ad0099e21d4fca87fa4ca92dda1a40af9e05d205e53f38bf026196fa2e431
                                            </p>
                                        </div>
                                        <div class="flex-shrink-0 text-end me-3">
                                            <h6 class="mb-1">+0.5 BTC</h6>
                                            <div class="font-size-13">$178.53</div>
                                        </div>

                                        <div class="flex-shrink-0 text-end">
                                            <div class="dropdown">
                                                <a class="text-muted dropdown-toggle font-size-24" role="button"
                                                    data-bs-toggle="dropdown" aria-haspopup="true">
                                                    <i class="mdi mdi-dots-vertical"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="index.html#">Action</a>
                                                    <a class="dropdown-item" href="index.html#">Another
                                                        action</a>
                                                    <a class="dropdown-item" href="index.html#">Something else here</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="index.html#">Separated link</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="activity-list activity-border">
                                <div class="activity-icon avatar-md">
                                    <span class="avatar-title  bg-primary-subtle text-primary rounded-circle">
                                        <i class="mdi mdi-ethereum font-size-24"></i>
                                    </span>
                                </div>
                                <div class="timeline-list-item">
                                    <div class="d-flex">
                                        <div class="flex-grow-1 overflow-hidden me-4">
                                            <h5 class="font-size-14 mb-1">24/05/2021, 18:24:56</h5>
                                            <p class="text-truncate text-muted font-size-13">
                                                0xb77ad0099e21d4fca87fa4ca92dda1a40af9e05d205e53f38bf026196fa2e431
                                            </p>
                                        </div>
                                        <div class="flex-shrink-0 text-end me-3">
                                            <h6 class="mb-1">-20.5 ETH</h6>
                                            <div class="font-size-13">$3541.45</div>
                                        </div>

                                        <div class="flex-shrink-0 text-end">
                                            <div class="dropdown">
                                                <a class="text-muted dropdown-toggle font-size-24" role="button"
                                                    data-bs-toggle="dropdown" aria-haspopup="true">
                                                    <i class="mdi mdi-dots-vertical"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="index.html#">Action</a>
                                                    <a class="dropdown-item" href="index.html#">Another
                                                        action</a>
                                                    <a class="dropdown-item" href="index.html#">Something else here</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="index.html#">Separated link</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="activity-list activity-border">
                                <div class="activity-icon avatar-md">
                                    <span class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                        <i class="bx bx-bitcoin font-size-24"></i>
                                    </span>
                                </div>
                                <div class="timeline-list-item">
                                    <div class="d-flex">
                                        <div class="flex-grow-1 overflow-hidden me-4">
                                            <h5 class="font-size-14 mb-1">24/05/2021, 18:24:56</h5>
                                            <p class="text-truncate text-muted font-size-13">
                                                0xb77ad0099e21d4fca87fa4ca92dda1a40af9e05d205e53f38bf026196fa2e431
                                            </p>
                                        </div>
                                        <div class="flex-shrink-0 text-end me-3">
                                            <h6 class="mb-1">+0.5 BTC</h6>
                                            <div class="font-size-13">$5791.45</div>
                                        </div>

                                        <div class="flex-shrink-0 text-end">
                                            <div class="dropdown">
                                                <a class="text-muted dropdown-toggle font-size-24" role="button"
                                                    data-bs-toggle="dropdown" aria-haspopup="true">
                                                    <i class="mdi mdi-dots-vertical"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="index.html#">Action</a>
                                                    <a class="dropdown-item" href="index.html#">Another
                                                        action</a>
                                                    <a class="dropdown-item" href="index.html#">Something else here</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="index.html#">Separated link</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="activity-list activity-border">
                                <div class="activity-icon avatar-md">
                                    <span class="avatar-title  bg-primary-subtle text-primary rounded-circle">
                                        <i class="mdi mdi-litecoin font-size-24"></i>
                                    </span>
                                </div>
                                <div class="timeline-list-item">
                                    <div class="d-flex">
                                        <div class="flex-grow-1 overflow-hidden me-4">
                                            <h5 class="font-size-14 mb-1">24/05/2021, 18:24:56</h5>
                                            <p class="text-truncate text-muted font-size-13">
                                                0xb77ad0099e21d4fca87fa4ca92dda1a40af9e05d205e53f38bf026196fa2e431
                                            </p>
                                        </div>
                                        <div class="flex-shrink-0 text-end me-3">
                                            <h6 class="mb-1">-1.5 LTC</h6>
                                            <div class="font-size-13">$5791.45</div>
                                        </div>

                                        <div class="flex-shrink-0 text-end">
                                            <div class="dropdown">
                                                <a class="text-muted dropdown-toggle font-size-24" role="button"
                                                    data-bs-toggle="dropdown" aria-haspopup="true">
                                                    <i class="mdi mdi-dots-vertical"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="index.html#">Action</a>
                                                    <a class="dropdown-item" href="index.html#">Another
                                                        action</a>
                                                    <a class="dropdown-item" href="index.html#">Something else here</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="index.html#">Separated link</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>


                            <li class="activity-list activity-border">
                                <div class="activity-icon avatar-md">
                                    <span class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                        <i class="bx bx-bitcoin font-size-24"></i>
                                    </span>
                                </div>
                                <div class="timeline-list-item">
                                    <div class="d-flex">
                                        <div class="flex-grow-1 overflow-hidden me-4">
                                            <h5 class="font-size-14 mb-1">24/05/2021, 18:24:56</h5>
                                            <p class="text-truncate text-muted font-size-13">
                                                0xb77ad0099e21d4fca87fa4ca92dda1a40af9e05d205e53f38bf026196fa2e431
                                            </p>
                                        </div>
                                        <div class="flex-shrink-0 text-end me-3">
                                            <h6 class="mb-1">+0.5 BTC</h6>
                                            <div class="font-size-13">$5791.45</div>
                                        </div>

                                        <div class="flex-shrink-0 text-end">
                                            <div class="dropdown">
                                                <a class="text-muted dropdown-toggle font-size-24" role="button"
                                                    data-bs-toggle="dropdown" aria-haspopup="true">
                                                    <i class="mdi mdi-dots-vertical"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="index.html#">Action</a>
                                                    <a class="dropdown-item" href="index.html#">Another
                                                        action</a>
                                                    <a class="dropdown-item" href="index.html#">Something else here</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="index.html#">Separated link</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="activity-list">
                                <div class="activity-icon avatar-md">
                                    <span class="avatar-title  bg-primary-subtle text-primary rounded-circle">
                                        <i class="mdi mdi-litecoin font-size-24"></i>
                                    </span>
                                </div>
                                <div class="timeline-list-item">
                                    <div class="d-flex">
                                        <div class="flex-grow-1 overflow-hidden me-4">
                                            <h5 class="font-size-14 mb-1">24/05/2021, 18:24:56</h5>
                                            <p class="text-truncate text-muted font-size-13">
                                                0xb77ad0099e21d4fca87fa4ca92dda1a40af9e05d205e53f38bf026196fa2e431
                                            </p>
                                        </div>
                                        <div class="flex-shrink-0 text-end me-3">
                                            <h6 class="mb-1">+.55 LTC</h6>
                                            <div class="font-size-13">$91.45</div>
                                        </div>

                                        <div class="flex-shrink-0 text-end">
                                            <div class="dropdown">
                                                <a class="text-muted dropdown-toggle font-size-24" role="button"
                                                    data-bs-toggle="dropdown" aria-haspopup="true">
                                                    <i class="mdi mdi-dots-vertical"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="index.html#">Action</a>
                                                    <a class="dropdown-item" href="index.html#">Another
                                                        action</a>
                                                    <a class="dropdown-item" href="index.html#">Something else here</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="index.html#">Separated link</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div><!-- end row -->
@endsection

@section('script')
    <!-- dashboard init -->
    <script src="{{ asset('theme/admin/assets/js/pages/dashboard.init.js') }}"></script>


    <script>
        var options = {
            series: [30.0, 50.0, 20.0],
            chart: {
                type: 'donut',
            },
            labels: ['Ghế Thường', 'Ghế Vip', 'Ghế Đôi'], 
            colors: ['#2C91F7', '#2EE5AC', '#FF5733'], 
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val.toFixed(1) + "%";
                },
                style: {
                    fontSize: '12px',
                    fontWeight: 'bold'
                }
            },
            legend: {
                position: 'bottom',
                markers: {
                    width: 10,
                    height: 10,
                    radius: 10
                },
                fontSize: '12px'
            },
            tooltip: {
                enabled: true,
                y: {
                    formatter: function(val) {
                        return val.toFixed(1) + "%";
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#seat-booking-chart"), options);
        chart.render();
    </script>


    {{-- Biểu đồ doanh thu --}}
    <script>
        const revenueData = {
            "2025": [2.5, 3.2, 2.8, 3.5, 4.1, 3.9, 5.2, 4.8, 4.3, 3.7, 3.1, 2.9],
            "2024": [2.0, 2.8, 2.5, 3.0, 3.8, 3.6, 4.5, 4.2, 3.9, 3.5, 3.0, 2.7]
        };

        document.addEventListener("DOMContentLoaded", function() {
            var options = {
                series: [{
                    name: "Doanh thu (Triệu VND)",
                    data: revenueData["2025"]
                }],
                chart: {
                    type: 'line',
                    height: 350
                },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                xaxis: {
                    categories: ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6", "Tháng 7",
                        "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"
                    ]
                },
                yaxis: {
                    labels: {
                        formatter: function(value) {
                            return value + " triệu VND";
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + " triệu VND";
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#revenue-chart"), options);
            chart.render();

            document.getElementById("yearSelect").addEventListener("change", function() {
                var selectedYear = this.value;
                chart.updateSeries([{
                    name: "Doanh thu (Triệu VND)",
                    data: revenueData[selectedYear]
                }]);
            });
        });
    </script>
@endsection
