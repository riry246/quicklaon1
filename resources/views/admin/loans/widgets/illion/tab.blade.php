<div class="col-xl-12">
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">
                Statement
            </div>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs tab-style-1 d-sm-flex d-block" role="tablist">
                @foreach ($illionBankAccount as $key => $tab)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link @if ($key === 0) active @endif" data-bs-toggle="tab"
                            role="tab" href="#{{ str_replace(' ', '', $tab->account_number) }}" aria-selected="">
                            {{ $tab->name }} ({{ $tab->account_number }})
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content">
                @foreach ($illionBankAccount as $key => $tab)
                    <div class="tab-pane text-muted @if ($key === 0) show active @endif"
                        id="{{ str_replace(' ', '', $tab->account_number) }}" role="tabpanel">
                        @if ($tab->statement)
                            @include('admin.loans.widgets.illion.statement', $tab)
                        @else
                        @include('admin.loans.widgets.illion.transaction', $tab)
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
