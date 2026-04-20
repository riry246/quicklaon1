@php
    $tabs = [['id' => 'users', 'label' => 'Users'], ['id' => 'loan_applications', 'label' => 'Loan Applications'], ['id' => 'transactions', 'label' => 'Transactions']];
@endphp

<div class="card custom-card">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <ul class="nav nav-tabs justify-content-end nav-tabs-header mb-0" role="tablist">
                    @foreach ($tabs as $tab)
                        <li class="nav-item" role="presentation">
                            <a class="nav-link{{ $loop->first ? ' active' : '' }}" data-bs-toggle="tab" role="tab"
                                aria-current="page" href="#{{ $tab['id'] }}"
                                aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $tab['label'] }}
                                ({{ count(${$tab['id']}) }})</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="tab-content">
            @foreach ($tabs as $tab)
                <div class="tab-pane{{ $loop->first ? ' show active' : '' }} p-0 border-0" id="{{ $tab['id'] }}"
                    role="tabpanel">
                    @include('admin.search.widgets.' . $tab['id'])

                </div>
            @endforeach
        </div>
    </div>
</div>
