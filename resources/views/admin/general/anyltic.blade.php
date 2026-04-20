@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        @include('admin.general.pageheader')
        <!-- Page Header Close -->
    </div>


    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-betwen">
                    <div class="card-title">
                        Add {{ $topic }}
                    </div>
                </div>
                <div class="card-body">

                    <form class="row g-3 mt-0" method="POST" action="{{ route('analytics.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-5">
                                <label class="form-label">Factor</label>
                                <input type="text" name="value" class="form-control" placeholder="Name"
                                    aria-label="Name">
                                <input type="hidden" name="factor" value="{{ $topic }}" />
                            </div>
                            @if($topic == 'expenses')
                            <div class="col-md-5">
                                <label class="form-label">Factor Group</label>
                                <select class="form-control" data-trigger name="group_name" id="choices-single-default">
                                    <option value="basic-living" data-select2-id="select2-data-3-t8ko">Basic living expenses
                                    </option>
                                    <option value="discretionary-living" data-select2-id="select2-data-38-d53l">Discretionary Living
                                        Expenses</option>
                                </select>
                                <input type="hidden" name="factor" value="{{ $topic }}" />
                            </div>
                            @endif
                            <div class="col-2">
                                <button type="submit" class="btn btn-primary mt-4">Add</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        <i class="bi bi-person-check text-secondary "></i> &nbsp {{ $topic }} Factor List
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

                                    <th class="text-muted">Factor</th>

                                    <th class="text-muted">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($list as $l)
                                    <tr>
                                        <td>
                                            {{ $l->value }}
                                        </td>
                                        <td>
                                            <div class="mb-md-0 mb-2">

                                                <a href="{{ route('analytics.delete', ['slug' => $topic, 'id' => $l->id]) }}"
                                                    class="btn btn-icon btn-danger-transparent rounded-pill btn-wave me-5"
                                                    title="Delete">
                                                    <i class="ri-delete-bin-line"></i>
                                                </a>

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
@endsection
