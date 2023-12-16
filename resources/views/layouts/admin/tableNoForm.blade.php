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
                <h2 class="fs-2x fw-bold mb-0">{{ ucfirst($title) }} Data masih Kosong</h2>
                <!--end::Title-->
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
                                    } elseif (substr($item['name'], -1) == 's') {
                                        $name = substr($item['name'], 0, -1);
                                    } elseif (str_contains($item['name'], '_enum')) {
                                        $name = str_replace('_enum', '', $item['name']);
                                    }
                                @endphp
                                <th class="text-center" style="min-width: 120px;">
                                    {{ ucfirst(str_replace('_', ' ', $name)) }}</th>
                            @endforeach
                            <th class="text-center">Diperbarui pada</th>
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
                                            </span>
                                        </td>
                                    @endif
                                @endforeach
                                <td class="text-center" style="min-width: 180px;">
                                    <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">
                                        {{ Carbon\Carbon::parse($item->updated_at)->isoFormat('DD MMMM YYYY') }}
                                    </span>
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
