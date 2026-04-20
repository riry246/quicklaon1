@extends('layouts.admin')
@inject('setting_helper', 'App\Http\Helpers\SettingHelper')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        @include('admin.general.pageheader')
        <!-- Page Header Close -->
    </div>

    <form class="row g-3 mt-0" method="POST" action="{{ route('setting.update', $slug) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
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
                                                    value="{{ $ff['value'] ? $ff['value'] : old($ff['name']) }}"
                                                    class="form-control" placeholder="{{ $ff['placeholder'] }}"
                                                    aria-label="{{ $ff['name'] }}">
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
                                                <input type="date" name="{{ $ff['name'] }}" class="form-control"
                                                    id="input-date">
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
                                                <textarea name="{{ $ff['name'] }}" class="form-control">{{ $ff['value'] ? $ff['value'] : old($ff['name']) }}</textarea>
                                                @if ($errors->has($ff['name']))
                                                    <span class="error_msg" role="alert">{{ $errors->first($ff['name']) }}</span>
                                                @endif
                                            </div>
                                        @break

                                        @case('file')
                                            <div class="col-md-{{ $ff['size'] }} mt-3">
                                                <label for="inputEmail4" class="form-label">{{ $ff['placeholder'] }}</label>
                                                <input class="form-control" type="file" id="input-file"
                                                    name="{{ $ff['name'] }}">
                                                @if ($ff['value'])
                                                    @if ($setting_helper->attribute($ff['value']) == 'logo')
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-6 col-12 mt-3"> <span
                                                                    class="avatar avatar-xxl me-2"> <img {{-- src="{{asset('/storage/uploads/settings/General/Logo/' . $ff['value'])}}" --}}
                                                                        src="{{ Storage::url('/uploads/settings/General/Logo/' . $ff['value']) }}"
                                                                        alt="img"> </span>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-6 col-12 mt-3"> <span
                                                                    class="avatar avatar-xxl me-2"> <img {{-- src="{{asset('/storage/uploads/settings/General/Logo/' . $ff['value'])}}" --}}
                                                                        src="{{ Storage::url('/uploads/settings/General/Small Logo/' . $ff['value']) }}"
                                                                        alt="img"> </span>
                                                            </div>
                                                        </div>
                                                    @endif
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
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
@endsection
