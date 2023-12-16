@extends('layouts.admin.app')
@section('content')
    @php
        $currenturl = url()->current();
    @endphp
    <!--begin::Card-->
    @if ($data->count() < 1)
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
                    <a href="#" class="btn btn-primary er fs-6 px-8 py-4" data-bs-toggle="modal"
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
                    <a href="#" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#addModal"
                        id="addButton">
                        <i class="ki-duotone ki-plus fs-2"></i>New {{ ucfirst($title) }}</a>
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
                                <th class="ps-4 min-w-325px rounded-start">Name</th>
                                <th class="min-w-125px">Email</th>
                                <th class="min-w-125px">Facebook</th>
                                <th class="min-w-200px">Instagram</th>
                                <th class="min-w-150px">Twitter</th>
                                <th class="min-w-150px">linkedin</th>
                                <th class="min-w-100px text-end rounded-end"></th>
                            </tr>
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-50px me-5">
                                                <img src="{{ isset($item->photo) ? asset($item->photo) : asset('assets/metronic/media/stock/600x400/img-26.jpg') }}"
                                                    class="" alt="" />
                                            </div>
                                            <div class="d-flex justify-content-start flex-column">
                                                <a href="#"
                                                    class="text-dark fw-bold text-hover-primary mb-1 fs-6">{{ ucfirst($item->name) }}</a>
                                                <span
                                                    class="text-muted fw-semibold text-muted d-block fs-7">{{ ucfirst($item->roles[0]->name) }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{ $item->email }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{ $item->facebook ? $item->facebook : 'Belum ada Isi' }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{ $item->instagram ? $item->instagram : 'Belum ada Isi' }}</span>
                                    </td>
                                    <td>

                                        <span
                                            class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{ $item->twitter ? $item->twitter : 'Belum ada Isi' }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{ $item->twitter ? $item->lnkedin : 'Belum ada Isi' }}</span>
                                    </td>
                                    <td class="text-end d-flex">
                                        <button data-bs-toggle="modal" id="editform" data-url="{{ $currenturl . '/' . $item->id }}" type="button" data-bs-target="#addModal"
                                            class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 editform">
                                            <i class="ki-duotone ki-pencil fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </button>
                                        <form action="{{ $currenturl . '/' . $item->id }}" method="post" id="deleteForm{{ $item->id }}" enctype="multipart/form-data">
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
                    <!--end::Table-->
                </div>
                <!--end::Table container-->
            </div>
            <!--begin::Body-->
        </div>
    @endif
    <!--end::Card-->
@endsection
@section('script')
@endsection
