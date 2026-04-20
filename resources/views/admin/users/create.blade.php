@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Create New User</h1>
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
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            Basic Information
                        </div>
                    </div>
                    <div class="card-body">

                        <form class="row g-3 mt-0" method="POST" action="{{ route('user.store') }}">
                        @csrf
                            <div class="col-md-4">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" placeholder="First name"
                                    aria-label="First name">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Middle Name</label>
                                <input type="text" name="middle_name" class="form-control" placeholder="Middle name"
                                    aria-label="First name">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" placeholder="Last name"
                                    aria-label="Last name">
                            </div>
                            <div class="col-md-6">
                                <label for="inputEmail4" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" id="inputEmail4">
                            </div>
                            <div class="col-md-6">
                                <label for="inputEmail4" class="form-label">Mobile</label>
                                <input type="tel" name="mobile" class="form-control" id="input-tel">
                            </div>
                            <div class="col-md-6">
                                <label for="inputEmail4" class="form-label">Date of Birth</label>
                                <input type="date" name="dob" class="form-control" id="input-date">
                            </div>
                            <div class="col-md-6">
                                <label for="inputPassword4" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="inputPassword4">
                            </div>
                            <div class="col-md-4">
                                <label for="inputState" class="form-label">Group</label>
                                <select class="form-control" data-trigger name="status" id="choices-single-default">
                                    <option value="">Choose group</option>
                                    @foreach ($groups as $g)
                                        <option value="{{ $g->id }}">{{ $g->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-4 p-5">
                                <div class="custom-toggle-switch d-flex align-items-center mb-4">
                                    <input id="toggleswitchSuccess" name="toggleswitch001" type="checkbox" checked="">
                                    <label for="toggleswitchSuccess" class="label-success"></label><span
                                        class="ms-3">Status</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Add User</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer d-none border-top-0">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
