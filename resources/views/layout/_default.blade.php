@extends('layout.master')

@section('content')
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            @include('layout.partials.sidebar-layout._header')
            
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                @include('layout.partials.sidebar-layout._sidebar')
                
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <div class="d-flex flex-column flex-column-fluid">
                        @include('layout.partials.sidebar-layout._toolbar')
                        
                        <div id="kt_app_content" class="app-content flex-column-fluid">
                            <div id="kt_app_content_container" class="app-container container-fluid">
                                @yield('page-content')
                            </div>
                        </div>
                    </div>
                    @include('layout.partials.sidebar-layout._footer')
                </div>
            </div>
        </div>
    </div>
@endsection
