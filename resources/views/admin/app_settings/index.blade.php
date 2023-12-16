@extends('layouts.admin.app')
@section('content')
    <form id="" class="form" action="{{ route(Route::currentRouteName()) . '/' . $data->id }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <!--begin::Input group-->
        {!! $formHelper['makeForm'] !!}
        <!--begin::Actions-->
        <div class="text-end">
            <button type="submit" class="btn btn-primary">
                <span class="indicator-label">Update</span>
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
                        $(`.photoForm.${key}`).attr("src", val);
                        $(`.imageForm.${key}`).attr("src", val);
                    } else {
                        $(`input[name=${key}]`).val(val)
                        $(`select[name=${key}]`).val(val)
                        $(`select[name=${key}]`).trigger('change')
                        $(`textarea[name=${key}]`).html(val)
                    }
                });
                $('[name=role]').val('{{ auth()->user()->roles[0]->name }}')
                $('[name=role]').trigger('change');
            }
        });
    </script>
@endsection
