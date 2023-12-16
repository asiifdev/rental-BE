@php
    $currenturl = url()->current();
@endphp

<style>
    p>img {
        max-width: 100%;
    }
</style>
<!--begin::Card-->
@if (count($data) < 1)
    <div class="card">
        <!--begin::Card body-->
        <div class="card-body">
            <!--begin::Heading-->
            <div class="card-px text-center pt-15 pb-15">
                <!--begin::Title-->
                <h2 class="fs-2x fw-bold mb-0">Add new {{ ucfirst($title) }} Data</h2>
                <!--end::Title-->
                <!--begin::Description-->
                <p class="text-gray-400 fs-4 fw-semibold py-7">Click on the below buttons to launch
                    <br />a new {{ $title }}.
                </p>
                <!--end::Description-->
                <!--begin::Action-->
                <a href="#" class="btn btn-primary er fs-6 px-8 py-4 addButton" data-bs-toggle="modal"
                    data-bs-target="#addModal">Add New {{ $title }}</a>
                <!--end::Action-->
            </div>
            <!--end::Heading-->
            <!--begin::Illustration-->
            <div class="text-center pb-15 px-5">
                <img src="{{ asset('assets/metronic/media/illustrations/sketchy-1/17.png') }}" alt=""
                    class="mw-100 h-200px h-sm-325px" />
            </div>
            <!--end::Illustration-->
        </div>
        <!--end::Card body-->
    </div>
@else
    <div class="card mb-5 mb-xl-8">
        <!--begin::Header-->
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold fs-3 mb-1">{{ ucfirst($title) }} Data</span>
                <span class="text-muted mt-1 fw-semibold fs-7">Total {{ $data->count() }} {{ $title }}
                    datas</span>
            </h3>
            <div class="card-toolbar">
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-light-primary addButton" data-bs-toggle="modal"
                        data-bs-target="#addModal">
                        <i class="ki-duotone ki-plus fs-2"></i>New {{ ucfirst($title) }}</button>
                </div>
            </div>
        </div>
        <!--end::Header-->
        <!--begin::Body-->
        <div class="card-body py-3">
            <!--begin::Table container-->
            <div class="table-responsive">
                <!--begin::Table-->
                <table class="table align-middle gs-0 gy-4">
                    <!--begin::Table head-->
                    <thead>
                        <tr class="fw-bold text-muted bg-light">
                            <th class="text-center">No</th>
                            @foreach ($formHelper['getTable'] as $item)
                                @php
                                    $name = str_replace('_id', '', $item['name']);
                                    $name = implode('_', array_map('ucfirst', explode('_', $name)));
                                    if (substr($item['name'], -3) == 'ies') {
                                        $name = substr($item['name'], 0, -3) . 'y';
                                    } elseif (substr($item['name'], -2) == 'as' || substr($item['name'], -2) == 'es' || substr($item['name'], -2) == 'us' || substr($item['name'], -2) == 'is' || substr($item['name'], -2) == 'os') {
                                        $name = $item['name'];
                                    } elseif (substr($item['name'], -1) == 's') {
                                        $name = substr($item['name'], 0, -1);
                                    } elseif (str_contains($item['name'], '_enum')) {
                                        $name = str_replace('_enum', '', $item['name']);
                                    }
                                @endphp
                                <th class="text-center" style="min-width: 120px;">
                                    {{ ucfirst(str_replace('_', ' ', $name)) }}</th>
                            @endforeach
                            <th class="text-center">Dibuat Pada</th>
                            <th class="min-w-100px text-end rounded-end"></th>
                        </tr>
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <th scope="row" class="text-center">{{ $loop->iteration }}</th>
                                @foreach ($formHelper['getTable'] as $col)
                                    @php
                                        $name = $col['name'];
                                        $type = $col['type'];
                                    @endphp
                                    @if ($type == 'datetime')
                                        <td class="text-center">
                                            <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">
                                                {{ Carbon\Carbon::parse($item->$name)->isoFormat('DD MMMM YYYY') }}
                                            </span>
                                        </td>
                                    @elseif ($name == 'font_icon')
                                        <td class="text-center">
                                            <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">
                                                <i class="{{ $item->$name }}"></i>
                                            </span>
                                        </td>
                                    @elseif($name == 'image' || $name == 'thumbnail' || $name == 'logo' || $name == 'icon')
                                        <td class="text-center">
                                            @if ($item->$name != null)
                                                <img src="{{ asset($item->$name) }}" alt="{{ $item->$name }}"
                                                    width="200px" class="img-fluid">
                                            @else
                                                <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">
                                                    {{ ucfirst($name) }} belum diupload
                                                </span>
                                            @endif
                                        </td>
                                    @elseif($type == 'text')
                                        <td class="{{ strlen($item->$name) > 1000 || $name == 'content' ? 'text-center' : '' }}"
                                            style="min-width: {{ strlen($item->$name) > 1000 || $name == 'content' ? 200 : (strlen($item->$name) * 10) / 2 }}px;">
                                            <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">
                                                @if (strlen($item->$name) > 1000 || $name == 'content')
                                                    <button type="button" class="btn btn-sm btn-light-primary"
                                                        data-bs-toggle="modal"
                                                        onclick="setCustomElement('{{ $item->$name }}')"
                                                        data-bs-target="#customElementModal">
                                                        Show
                                                    </button>
                                                @else
                                                    {!! $item->$name !!}
                                                @endif
                                            </span>
                                        </td>
                                    @elseif($name == 'link')
                                        <td class="">
                                            <a href="{{ $item->$name }}" target="_blank" rel="noopener noreferrer">
                                                <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">
                                                    {{ $item->$name }}
                                                </span>
                                            </a>
                                        </td>
                                    @elseif(str_ends_with($name, 'user_id'))
                                        @php
                                            $relation = 'user';
                                        @endphp
                                        <td class="text-center"
                                            style="min-width: {{ strlen($item->$relation->name) * 10 + 4 }}px;">
                                            <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">
                                                {{ $item->$relation->name }}
                                            </span>
                                        </td>
                                    @elseif(str_contains($name, '_id'))
                                        @php
                                            $relation = str_replace('_id', '', $name);
                                        @endphp
                                        <td class="text-center"
                                            style="min-width: {{ strlen($item->$relation->name) * 10 + 4 }}px;">
                                            <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">
                                                {{ $item->$relation->name }}
                                            </span>
                                        </td>
                                    @else
                                        <td class="text-center"
                                            style="min-width: {{ strlen($item->$name) * 10 + 4 }}px;">
                                            <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">
                                                <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">
                                                    {{ $item->$name }}
                                                </span>
                                                {{-- @endif --}}
                                            </span>
                                        </td>
                                    @endif
                                @endforeach
                                <td class="text-center" style="min-width: 180px;">
                                    <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">
                                        {{ Carbon\Carbon::parse($item->created_at)->isoFormat('DD MMMM YYYY') }}
                                    </span>
                                </td>
                                <td class="text-end d-flex">
                                    <button data-bs-toggle="modal" id="editform"
                                        data-url="{{ $currenturl . '/' . $item->id }}" type="button"
                                        data-bs-target="#addModal"
                                        class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 editform">
                                        <i class="ki-duotone ki-pencil fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </button>
                                    <form action="{{ $currenturl . '/' . $item->id }}" method="post"
                                        id="deleteForm{{ $item->id }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="deleteForm('deleteForm{{ $item->id }}')"
                                            class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">
                                            <i class="ki-duotone ki-trash fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                                <span class="path5"></span>
                                            </i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <!--end::Table body-->
                </table>
                <div class="mt-5">
                    {{ $data->links() }}
                </div>
                <!--end::Table-->
            </div>
            <!--end::Table container-->
        </div>
        <!--begin::Body-->
    </div>
@endif

<div class="modal fade" id="customElementModal" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!--begin::Modal content-->
        <div class="modal-content rounded">
            <!--begin::Modal header-->
            <div class="modal-header pb-0 border-0 justify-content-between">
                <h5>Blog Show</h5>
                <!--begin::Close-->
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal"
                    onclick="resetCustomElement()">
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
                <div id="customContent"></div>
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>

<script>
    function resetCustomElement() {
        $('#customContent').fadeIn("slow").html('');
    }

    function setCustomElement(html) {
        $('#customContent').fadeIn("slow").html(html)
    }
</script>
<!--end::Card-->
