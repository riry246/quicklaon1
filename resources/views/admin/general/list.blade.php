@extends('layouts.admin')
@inject('loan_helper', 'App\Http\Helpers\LoanHelper')
@section('content')
    <div class="container-fluid">

        <!-- Page Header -->
        @include('admin.general.pageheader')
        <!-- Page Header Close -->

        <!-- Start:: row-5 -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            <i class="bi bi-person-check text-secondary "></i> &nbsp {{ $breadcrumb['function'] }}s
                        </div>
                        <div>
                            @if ($btn['url'])
                                <a href="{{ route($btn['url']) }}" class="btn btn-primary mb-2 " title="{{ $btn['name'] }}">
                                    {{ $btn['name'] }} {{ $breadcrumb['function'] }}
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="deleted-table table-responsive">
                            <div class="justify-content-between">
                                <span class="fw-bold text-dark data-table-btn">{{ count($list) }} Records</span>
                            </div>
                            <table id="delete-datatable" class="table  table-search mb-4 mt-4">
                                <thead>
                                    <tr>
                                        @foreach ($tabel_fields as $t)
                                            <th class="text-muted">{{ ucfirst(str_replace('_', ' ', $t)) }}</th>
                                        @endforeach
                                        <th class="text-muted">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($list as $l)
                                        <tr>
                                            @foreach ($tabel_fields as $t)
                                                @if ($t == 'id')
                                                    <td>{{ $i++ }}</td>
                                                @elseif($t == 'image')
                                                    @if ($l->$t)
                                                        <td>

                                                            <a href="javascript:void();" class="glightbox card"
                                                                data-gallery="gallery1">
                                                                <img src="{{ Storage::url('public/images/' . $l->$t) }}"
                                                                    alt="img" width="100" class="mx-auto p-3">
                                                            </a>


                                                        </td>
                                                    @else
                                                        <td>Image Not available</td>
                                                    @endif
                                                @elseif($t == 'logo')
                                                    @if ($l->$t)
                                                        <td>

                                                            <a href="javascript:void();" class="glightbox card"
                                                                data-gallery="gallery1">
                                                                <img src="{{ asset('assets/' . $l->$t) }}"" alt="img"
                                                                    width="100" class="mx-auto p-3">
                                                            </a>



                                                        </td>
                                                    @else
                                                        <td>Image Not available</td>
                                                    @endif
                                                @elseif($t == 'group')
                                                    @foreach ($l->groups as $group)
                                                        <td> {{ $group->name }} </td>
                                                    @endforeach
                                                @elseif($t == 'expiry_date')
                                                    @if ($l->$t < Carbon\Carbon::now()->format('Y-m-d'))
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-danger btn-icon btn-sm">Expired</button>
                                                        </td>
                                                    @else
                                                        <td>
                                                            {{ $l->$t }}
                                                        </td>
                                                    @endif
                                                @elseif($t == 'created_at' || $t == 'application_date')
                                                    <td>{{ $loan_helper->formateDate($l->$t) }}</td>
                                                @elseif($t == 'status')
                                                    @if ($l->$t == 'active' || $l->$t == 'Complete')
                                                        <td>
                                                            <span
                                                                class="badge bg-success p-2 fs-12">{{ ucfirst($l->status) }}</span>
                                                        </td>
                                                    @else
                                                        <td>
                                                            <span
                                                                class="badge bg-danger p-2  fs-12">{{ ucfirst($l->status) }}</span>
                                                        </td>
                                                    @endif
                                                @elseif($t == 'user')
                                                    <td>
                                                        {{ $loan_helper->getUserName($l->user_id) }}
                                                    </td>
                                                @else
                                                    <td>{{ $l->$t }}</td>
                                                @endif
                                            @endforeach
                                            <td>
                                                <div class="mb-md-0 mb-2">
                                                    @foreach ($action_btn as $a)
                                                        <a href="{{ route($a['url'], $l->id) }}"
                                                            class="btn btn-icon {{ $a['class'] }}"
                                                            title="{{ $a['name'] }}">
                                                            <i class="{{ $a['icon'] }}"></i>
                                                        </a>
                                                    @endforeach
                                                </div>

                                            </td>

                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End:: row-5 -->

    </div>
@endsection
