<!--begin::Header-->
<div id="kt_app_header" class="app-header" data-kt-sticky="true" data-kt-sticky-activate="{default: false, lg: true}"
    data-kt-sticky-name="app-header-sticky" data-kt-sticky-offset="{default: false, lg: '300px'}">
    <!--begin::Header container-->
    <div class="app-container container-fluid d-flex flex-stack" id="kt_app_header_container">
        <!--begin::Sidebar toggle-->
        <div class="d-flex align-items-center d-block d-lg-none ms-n3" title="Show sidebar menu">
            <div class="btn btn-icon btn-active-color-primary w-35px h-35px me-2" id="kt_app_sidebar_mobile_toggle">
                <i class="ki-duotone ki-abstract-14 fs-1">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </div>
            <!--begin::Logo image-->
            <a href="{{ url('admin') }}">
                <img alt="{{ $company->name }}" src="{{ asset($company->icon) . "?version=" . Carbon\Carbon::now()->format('dm') }}"
                    class="h-30px theme-light-show" />
                <img alt="{{ $company->name }}" src="{{ asset($company->icon) . "?version=" . Carbon\Carbon::now()->format('dm') }}"
                    class="h-30px theme-dark-show" />
            </a>
            <!--end::Logo image-->
        </div>
        <!--end::Sidebar toggle-->
        <!--begin::Header wrapper-->
        <div class="d-flex flex-stack flex-lg-row-fluid" id="kt_app_header_wrapper">
            <!--begin::Page title-->
            <div class="page-title gap-4 me-3 mb-5 mb-lg-0" data-kt-swapper="true"
                data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}"
                data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_header_wrapper'}">
                <div class="d-flex align-items-center mb-3">
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-gray-700 fw-bold lh-1 mx-n1">
                            <a href="{{ url('admin') }}" class="text-hover-primary">
                                <i class="ki-duotone ki-home text-gray-700 fs-6"></i>
                            </a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <i class="ki-duotone ki-right fs-7 text-gray-700"></i>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-gray-500 mx-n1">{{ $breadcrumbs['parent'] }}</li>
                        <!--end::Item-->
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--begin::Title-->
                <h1 class="text-gray-900 fw-bolder m-0">{{ $breadcrumbs['child'] }}</h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
            <a href="{{ url('admin') }}">
                <img alt="Logo" src="{{ asset($company->logo) . "?version=" . Carbon\Carbon::now()->format('dm') }}" class="me-7 d-none d-lg-inline h-60px" />
            </a>
            @include('sweetalert::alert')
        </div>
        <!--end::Header wrapper-->
    </div>
    <!--end::Header container-->
</div>
<!--end::Header-->
