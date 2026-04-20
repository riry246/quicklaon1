@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        @include('admin.general.pageheader')
        <!-- Page Header Close -->
    </div>

    @if (isset($form['attribute_id']))
        <form action="{{ route($form['action'], $form['attribute_id'] ?? null, $form['id']) }}" method="post"
            enctype="multipart/form-data" class="row g-3 mt-0">
        @else
            <form action="{{ route($form['action'], $form['id']) }}" method="post" enctype="multipart/form-data"
                class="row g-3 mt-0">
    @endif

    @csrf
    <div class="row">
        @foreach ($form['form_elements'] as $f)
            <div class="col-xl-{{ $f['size'] }}">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            {{ $f['form_name'] }}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-6 mt-3">
                                <label for="inputState" class="form-label">Module</label>
                                {{ Form::select('module_id', $module->pluck('name', 'id'), Request::get('name'), [
                                    'class' => 'form-control',
                                    'id' => 'name',
                                    'placeholder' => 'Choose Module',
                                    'data-trigger',
                                ]) }}
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="inputState" class="form-label">Action</label>
                                {{ Form::select('action[]', $permissions, Request::get('action'), [
                                    'class' => 'form-control js-example-basic-multiple',
                                    'id' => 'action',
                                    'data-trigger',
                                    'multiple' => true
                                ]) }}
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-none border-top-0">
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
    </form>
@endsection
