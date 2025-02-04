@extends('admin.layouts.master')
@section('title', 'Show Cinema')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Cinemas Managers</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.cinemas.index') }}">Cinemas</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $cinema->name }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-lg-12">
        <h1>Show Cinema: <span class="fst-italic text-danger">{{ $cinema->name }}</span></h1>
        <h1>Branch: <span class="fst-italic text-danger">{{ $cinema->branch->name }}</span></h1>
        <h1>Address Cinema: <span class="fst-italic text-danger">{{ $cinema->address }}</span></h1>
    </div>
</div>
@endsection
