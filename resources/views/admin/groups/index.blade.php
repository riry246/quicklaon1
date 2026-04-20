@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">User Management</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">User Management</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Start:: row-5 -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            {{ count($users) }} Users
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="deleted-table table-responsive">
                            <div class="text-center">
                                <a href="{{ route('user.create') }}" id="button"
                                    class="btn btn-primary mb-2 data-table-btn">Add new</a>
                            </div>
                            <table id="delete-datatable" class="table table-search text-nowrap mt-4 mb-4">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email Address</th>
                                        <th>Phone Number</th>
                                        <th>Group</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $u)
                                        <tr>
                                            <td>{{ $u->first_name }} {{ $u->middle_name }} {{ $u->last_name }}</td>
                                            <td>{{ $u->email }}</td>
                                            <td>{{ $u->mobile }}</td>
                                            @foreach ($u->groups as $g)
                                                <td>{{ $g->name }}</td>
                                            @endforeach
                                            <td>
                                                @if ($u->status == 'active')
                                                    <span class="badge bg-success">{{ ucfirst($u->status) }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ ucfirst($u->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="mb-md-0 mb-2">
                                                    <a href="{{ route('user.edit', ['user_id' => $u->id]) }}"
                                                        class="btn btn-icon btn-primary-transparent rounded-pill btn-wave">
                                                        <i class="ri-edit-line"></i>
                                                    </a>
                                                    <a href="{{ route('user.show', ['user_id' => $u->id]) }}"
                                                        class="btn btn-icon btn-secondary-transparent rounded-pill btn-wave">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                    <a href="{{ route('user.delete', ['user_id' => $u->id]) }}"
                                                        class="btn btn-icon btn-danger-transparent rounded-pill btn-wave me-5">
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
        <!-- End:: row-5 -->

    </div>
@endsection
