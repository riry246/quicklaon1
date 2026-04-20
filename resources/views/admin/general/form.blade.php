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
                            @foreach ($f['form_fields'] as $ff)
                                @switch($ff['type'])
                                    @case('text')
                                        <div class="col-md-{{ $ff['size'] }} mt-3">
                                            <label class="form-label">{{ $ff['placeholder'] }}</label>
                                            <input type="{{ $ff['type'] }}" name="{{ $ff['name'] }}"
                                                value="{{ $ff['value'] ? $ff['value'] : old($ff['name']) }}" class="form-control"
                                                placeholder="{{ $ff['placeholder'] }}" aria-label="{{ $ff['name'] }}">
                                            @if ($errors->has($ff['name']))
                                                <span class="error_msg" role="alert">{{ $errors->first($ff['name']) }}</span>
                                            @endif
                                        </div>
                                    @break

                                    @case('password')
                                        <div class="col-md-{{ $ff['size'] }} mt-3">
                                            <label class="form-label">{{ $ff['placeholder'] }}</label>
                                            <input type="{{ $ff['type'] }}" name="{{ $ff['name'] }}" class="form-control"
                                                placeholder="{{ $ff['placeholder'] }}" aria-label="{{ $ff['name'] }}">
                                            @if ($errors->has($ff['name']))
                                                <span class="error_msg" role="alert">{{ $errors->first($ff['name']) }}</span>
                                            @endif
                                        </div>
                                    @break

                                    @case('date')
                                        <div class="col-md-{{ $ff['size'] }} mt-3">
                                            <label for="inputEmail4" class="form-label">{{ $ff['placeholder'] }}</label>
                                            <input type="date"
                                                name="{{ $ff['name'] }}"value="{{ $ff['value'] ? $ff['value'] : old($ff['name']) }}"
                                                class="form-control" id="input-date">
                                            @if ($errors->has($ff['name']))
                                                <span class="error_msg" role="alert">{{ $errors->first($ff['name']) }}</span>
                                            @endif
                                        </div>
                                    @break

                                    @case('dropdown')
                                        <div class="col-md-{{ $ff['size'] }} mt-3">
                                            <label for="inputState" class="form-label">{{ $ff['placeholder'] }}</label>
                                            <select class="form-control" data-trigger name="{{ $ff['name'] }}"
                                                id="choices-single-default">
                                                <option value="">Choose {{ $ff['placeholder'] }}</option>
                                                @foreach ($ff['options'] as $o)
                                                    <option @if ($o->id == $ff['value']) selected @endif
                                                        value="{{ $o->id }}">{{ $o->name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has($ff['name']))
                                                <span class="error_msg" role="alert">{{ $errors->first($ff['name']) }}</span>
                                            @endif
                                        </div>
                                    @break

                                    @case('textarea')
                                        <div class="col-md-{{ $ff['size'] }} mt-3">
                                            <label for="inputEmail4" class="form-label">{{ $ff['placeholder'] }}</label>
                                            <textarea name="{{ $ff['name'] }}" id="content" class="form-control" rows="5">{{ $ff['value'] ? $ff['value'] : old($ff['name']) }}</textarea>
                                            @if ($errors->has($ff['name']))
                                                <span class="error_msg" role="alert">{{ $errors->first($ff['name']) }}</span>
                                            @endif
                                        </div>
                                    @break

                                    @case('textbox')
                                        <div class="col-md-{{ $ff['size'] }} mt-3">
                                            <label for="inputEmail4" class="form-label">{{ $ff['placeholder'] }}</label>
                                            <textarea name="{{ $ff['name'] }}" class="form-control" rows="5">{{ $ff['value'] ? $ff['value'] : old($ff['name']) }}</textarea>
                                            @if ($errors->has($ff['name']))
                                                <span class="error_msg" role="alert">{{ $errors->first($ff['name']) }}</span>
                                            @endif
                                        </div>
                                    @break

                                    @case('file')
                                        <div class="col-md-{{ $ff['size'] }} mt-3">
                                            <label for="inputEmail4" class="form-label">{{ $ff['placeholder'] }}</label>
                                            <input class="form-control" type="file" id="input-file" name="{{ $ff['name'] }}">
                                            @if ($ff['value'])
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-6 col-12 mt-3"> <span
                                                            class="avatar avatar-xxl me-2"> <img
                                                                src="{{ Storage::url('public/images/' . $ff['value']) }}"
                                                                alt="img"> </span>
                                                    </div>

                                                </div>
                                            @endif
                                            @if ($errors->has($ff['name']))
                                                <span class="error_msg" role="alert">{{ $errors->first($ff['name']) }}</span>
                                            @endif
                                        </div>
                                    @break

                                    @case('multi')
                                        <div class="col-md-{{ $ff['size'] }} mt-3">
                                            <label for="inputEmail4" class="form-label">{{ $ff['placeholder'] }}</label>
                                            <input class="form-control" id="choices-text-preset-values" type="text"
                                                name="{{ $ff['name'] }}"
                                                value="{{ $ff['value'] ? $ff['value'] : old($ff['name']) }}"
                                                placeholder="This is a placeholder">
                                            @if ($errors->has($ff['name']))
                                                <span class="error_msg" role="alert">{{ $errors->first($ff['name']) }}</span>
                                            @endif
                                        </div>
                                    @break

                                    @case('checkbox')
                                        <div class="col-xl-{{ $ff['size'] }} pt-5">
                                            <div class="custom-toggle-switch d-flex align-items-center mb-4">
                                                <input id="toggleswitchSuccess" name="{{ $ff['name'] }}" type="checkbox"
                                                    @if ($ff['value'] == 'active') checked @endif>
                                                <label for="toggleswitchSuccess" class="label-success"></label><span
                                                    class="ms-3">{{ $ff['placeholder'] }}</span>
                                            </div>
                                        </div>
                                    @break

                                    @case('qr')
                                        <div class="col-xl-{{ $ff['size'] }} ">
                                            @if ($ff['secret'])
                                                <p>Set up your two factor authentication by scanning the barcode below.
                                                    Alternatively,
                                                    you can use the code <strong>{{ $ff['secret'] }}</strong></p>
                                                <div>
                                                    {!! $ff['value'] !!}
                                                </div>
                                                <p class="pt-3">You must set up your Google Authenticator app before continuing.
                                                    You will be unable
                                                    to login otherwise</p>
                                            @else
                                                <p>Not available</p>
                                                <div>
                                            @endif
                                        </div>
                                    @break

                                    @default
                                @endswitch
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer d-none border-top-0">
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="col-12 p-2">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
    </form>
@endsection
<style>
    .ck-content {
        min-height: 200px;
        color: #000
    }
</style>
