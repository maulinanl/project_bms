@extends('layout._default')

@section('title', 'BMS Dashboard')

@section('page-content')
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        
        <!-- HVAC Card -->
        <div class="col-md-6 col-lg-6 col-xl-4">
            <div class="card card-flush h-md-50 mb-5 mb-xl-10">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <div class="d-flex align-items-center">
                            {!! getIcon('abstract-26', 'fs-2x text-primary me-2') !!}
                            <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">24°C</span>
                        </div>
                        <span class="text-gray-500 pt-1 fw-semibold fs-6">HVAC Temperature</span>
                    </div>
                </div>
                <div class="card-body d-flex flex-column justify-content-end pe-0">
                    <span class="fs-6 fw-bolder text-gray-800 d-block mb-2">Status: Normal</span>
                    <div class="symbol-group symbol-hover flex-nowrap">
                        <div class="badge badge-light-success">Active</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Power Card -->
        <div class="col-md-6 col-lg-6 col-xl-4">
            <div class="card card-flush h-md-50 mb-5 mb-xl-10">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <div class="d-flex align-items-center">
                            {!! getIcon('electricity', 'fs-2x text-warning me-2') !!}
                            <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">85%</span>
                        </div>
                        <span class="text-gray-500 pt-1 fw-semibold fs-6">Power Usage</span>
                    </div>
                </div>
                <div class="card-body d-flex flex-column justify-content-end pe-0">
                    <span class="fs-6 fw-bolder text-gray-800 d-block mb-2">Load: 425 kW</span>
                    <div class="symbol-group symbol-hover flex-nowrap">
                        <div class="badge badge-light-warning">High</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Lighting Card -->
        <div class="col-md-6 col-lg-6 col-xl-4">
            <div class="card card-flush h-md-50 mb-5 mb-xl-10">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <div class="d-flex align-items-center">
                            {!! getIcon('sun', 'fs-2x text-info me-2') !!}
                            <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">78%</span>
                        </div>
                        <span class="text-gray-500 pt-1 fw-semibold fs-6">Lighting Level</span>
                    </div>
                </div>
                <div class="card-body d-flex flex-column justify-content-end pe-0">
                    <span class="fs-6 fw-bolder text-gray-800 d-block mb-2">342 Lights Active</span>
                    <div class="symbol-group symbol-hover flex-nowrap">
                        <div class="badge badge-light-success">Optimal</div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
    <div class="row g-5 g-xl-10">
        <div class="col-xl-12">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">Welcome to BMS Dashboard</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-6">Building Management System</span>
                    </h3>
                </div>
                <div class="card-body pt-6">
                    <div class="alert alert-primary d-flex align-items-center p-5">
                        <div class="d-flex flex-column">
                            <h4 class="mb-1 text-dark">Setup Complete!</h4>
                            <span>Your BMS dashboard is ready. Connect your IoT devices to start monitoring.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
