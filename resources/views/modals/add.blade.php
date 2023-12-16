<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!--begin::Modal content-->
        <div class="modal-content rounded">
            <!--begin::Modal header-->
            <div class="modal-header pb-0 border-0 justify-content-between">
                <!--begin::Close-->
                <h3>
                    Form {{ ucfirst($title) }}
                </h3>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal" onclick="hapus_field()">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
                <!--end::Close-->
            </div>
            <hr>
            <!--begin::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                <!--begin:Form-->
                <form id="addModal_form" class="form" action="{{ route(Route::currentRouteName()) }}" method="POST"
                    enctype="multipart/form-data" data-urlEdit="">
                    <input type="url" hidden name="edit_url" id="edit_url">
                    @csrf
                    @method('POST')
                    <!--begin::Heading-->
                    {{-- <div class="mb-13 text-center">
                        <!--begin::Title-->
                        <h1 class="mb-3">Add {{ $title }}</h1>
                        <!--end::Title-->
                        <!--begin::Description-->
                        <div class="text-muted fw-semibold fs-5">Add {{ $title }} Datas
                        </div>
                        <!--end::Description-->
                    </div> --}}
                    <!--end::Heading-->
                    <!--begin::Input group-->
                    {!! $formHelper['makeForm'] !!}
                    <!--begin::Actions-->
                    <div class="text-center">
                        <button type="reset" id="addModal_cancel" class="btn btn-light me-3">Cancel</button>
                        <button type="button" id="addModal_submit" class="btn btn-primary">
                            <span class="indicator-label">Submit</span>
                            <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end:Form-->
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>
