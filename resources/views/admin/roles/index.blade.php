@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Role Management</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Role Management</li>
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
                            2 Roles
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="deleted-table table-responsive">
                            {{-- <div class="text-center">
                                <a href="{{ route('user.create') }}" id="button"
                                    class="btn btn-primary mb-2 data-table-btn">Add new</a>
                            </div> --}}
                            <table id="delete-datatable" class="table table-search text-nowrap mt-4 mb-4">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($groupList as $group)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{$group->name}}</td>
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
