@extends('layouts.admin')
@inject('module_action_helper', 'App\Http\Helpers\ModuleActionHelper')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Group Management</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Group Management</li>
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
                            Create Group
                        </div>
                    </div>
                    <div class="card-body">

                        <form class="row g-3 mt-0" method="POST" action="{{ route('group.store') }}">
                            @csrf
                            <div class="col-md-12">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Name"
                                    aria-label="Name">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control"></textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Permissions</label>
                                <div class="table-responsive">
                                    <table class="table  table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Module</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            @foreach ($module_action_helper->dropdown() as $key => $moduleActions)
                                                <tr>
                                                    <td>{{ $i++ }}.
                                                        {{ $moduleActions[0]->module->name }}</td>
                                                    <td>
                                                        @foreach ($moduleActions as $key => $action)
                                                            <div class="col-3 mb-2">
                                                                <div class="form-check-danger form-check form-switch">
                                                                    <input class="form-check-input"
                                                                        value="{{ $action->id }}" type="checkbox"
                                                                        name="permissions[]">
                                                                    <label class="form-check-label"
                                                                        for="">{{ $action->action }}</label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-xl-4 p-5">
                                <div class="custom-toggle-switch d-flex align-items-center mb-4">
                                    <input id="toggleswitchSuccess" name="toggleswitch001" type="checkbox" checked="">
                                    <label for="toggleswitchSuccess" class="label-success"></label><span
                                        class="ms-3">Status</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Add Group</button>
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
