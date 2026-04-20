@if (session('success'))
    <div class="alert alert-solid-success alert-dismissible fs-15 fade show mb-4">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
    </div>
@endif
@if (session('warning'))
    <div class="alert alert-solid-danger alert-dismissible fs-15 fade show mb-4">
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i
                class="bi bi-x"></i></button>
    </div>
    <div class="alert alert-danger" id="warning" role="alert">
        {{ session('warning') }}
    </div>
@endif
@if (session('danger'))
    <div class="alert alert-solid-danger alert-dismissible fs-15 fade show mb-4">
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i
                class="bi bi-x"></i></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-solid-danger alert-dismissible fs-15 fade show mb-4">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i
                class="bi bi-x"></i></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-solid-danger alert-dismissible fs-15 fade show mb-4">
        <ul class="list-unstyled">
            @foreach ($errors->all() as $error)
                <li> {{ $error }} </li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i
                class="bi bi-x"></i></button>
    </div>
@endif
