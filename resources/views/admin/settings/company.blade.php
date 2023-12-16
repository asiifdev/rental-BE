@extends('layouts.admin.app')
@section('content')
    <form id="" class="form" action="{{ route(Route::currentRouteName()) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('POST')
        <!--begin::Input group-->
        {!! $formHelper['makeForm'] !!}
        <!--begin::Actions-->
        <div class="text-center">
            <button type="submit" id="" class="btn btn-primary">
                <span class="indicator-label">Save</span>
            </button>
        </div>
        <!--end::Actions-->
    </form>
@endsection
@section('script')
    <script>
        $.ajax({
            type: "GET",
            url: "{{ url()->current() . '/' . $data->id }}",
            success: function(response) {
                $.each(response, function(key, val) {
                    if (key == "role") {
                        $('#role').val(val)
                    } else if (key == "photo" || key == "image" || key ==
                        "thumbnail" || key == "icon" || key == "logo") {
                        $('.photoForm').attr("src", val)
                        $('.imageForm').attr("src", val)
                    } else {
                        $(`input[name=${key}]`).val(val)
                        $(`select[name=${key}]`).val(val)
                        $(`textarea[name=${key}]`).html(val)
                    }
                });
                $('#formRoles').attr('hidden', true);
            }
        });
    </script>
@endsection
