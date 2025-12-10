<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <a href="{{ route('dashboard') }}">
            <img alt="Logo" src="{{ image('logos/default-dark.svg') }}" class="h-25px app-sidebar-logo-default" />
            <img alt="Logo" src="{{ image('logos/default-small.svg') }}" class="h-20px app-sidebar-logo-minimize" />
        </a>
        
        <div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">
            {!! getIcon('black-left-line', 'fs-3 rotate-180 ms-1') !!}
        </div>
    </div>
    
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
            
            <div class="menu menu-column menu-rounded menu-sub-indention px-3 fw-semibold fs-6" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
                
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ request()->routeIs('dashboard') ? 'here show' : '' }}">
                    <span class="menu-link">
                        <span class="menu-icon">{!! getIcon('element-11', 'fs-2') !!}</span>
                        <span class="menu-title">Dashboard</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Overview</span>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="menu-item pt-5">
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7">BMS Monitoring</span>
                    </div>
                </div>
                
                <div class="menu-item">
                    <a class="menu-link" href="#">
                        <span class="menu-icon">{!! getIcon('abstract-26', 'fs-2') !!}</span>
                        <span class="menu-title">HVAC System</span>
                    </a>
                </div>
                
                <div class="menu-item">
                    <a class="menu-link" href="#">
                        <span class="menu-icon">{!! getIcon('electricity', 'fs-2') !!}</span>
                        <span class="menu-title">Power Distribution</span>
                    </a>
                </div>
                
                <div class="menu-item">
                    <a class="menu-link" href="#">
                        <span class="menu-icon">{!! getIcon('sun', 'fs-2') !!}</span>
                        <span class="menu-title">Lighting Control</span>
                    </a>
                </div>
                
            </div>
        </div>
    </div>
    
    <div class="app-sidebar-footer flex-column-auto pt-2 pb-6 px-6" id="kt_app_sidebar_footer">
        <a href="#" class="btn btn-flex flex-center btn-custom btn-primary overflow-hidden text-nowrap px-0 h-40px w-100">
            <span class="btn-label">BMS v1.0</span>
        </a>
    </div>
    
</div>
