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

        <!-- Main content -->
        <section class="content">

            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <h4 class="card-title">Assign roles to group</h4>
                    <a href="#" class="pull-right" data-toggle="tooltip" title="Go Back"><i
                                class="fa fa-arrow-circle-left fa-2x"></i></a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            {{--<form class="form-inline">--}}

                            {!! Form::open(['class'=>'form-inline','url'=>'roles/roleAccessIndex','method'=>'GET']) !!}
                            <div class="form-group col-sm-6 col-xs-6 col-md-3 col-lg-3">

                                {{Form::select('group_id',$groupList->pluck('name','id'),Request::get('group_id'),['class'=>'form-control','id'=>'group_id','placeholder'=>
                                'Select Group Name'])}}

                            </div>

                            <button type="submit" class="btn btn-primary save col-sm-3 col-xs-3 col-md-1 col-lg-1"
                                    name="filter">
                                <i class="fa fa-search" aria-hidden="true"></i>
                                Filter
                            </button>
                            {!! Form::close() !!}
                            {{--</form>--}}
                        </div>
                        <br>
                        <br>
                        {{--message flash--}}
                        <div class="col-lg-12">
                            {{-- @if($menus) --}}
                                <div class="table-responsive">
                                    <table class="table  table-striped table-hover">
                                        <thead>
                                        <tr>
                                            <th>Module</th>
                                            <th>Read?</th>
                                            <th>Write?</th>
                                            <th>Edit?</th>
                                            <th>Delete?</th>
                                        </tr>
                                        </thead>
                                        <tbody>


                                        </tbody>
                                    </table>
                                </div>
                            {{-- @else
                                <div class="callout callout-info">
                                    Please select the group name from above drop down menu.
                                </div>
                            @endif --}}
                        </div>

                    </div>


                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </section>
        <!-- /.content -->

    </div>
@endsection
