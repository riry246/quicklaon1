<div class="card-body mx-3">
    @php
        $tabs = [
            [
                'id' => 'account-summary',
                'label' => 'Account Summary',
                'icon' => 'bi bi-diagram-3',
                'active' => true,
                'page' => 'admin.loans.widgets.illion.accountSummary',
            ],
            [
                'id' => 'consumer-affortdability',
                'label' => 'Consumer Affortdability Report',
                'icon' => 'bi bi-person',
                'active' => false,
                'page' => 'admin.loans.widgets.illion.consumerAffordability',
            ],
            [
                'id' => 'account-statement',
                'label' => 'Account Statement',
                'icon' => 'bi bi-bank2',
                'active' => false,
                'page' => 'admin.loans.widgets.illion.tab',
            ],
        ];
    @endphp

    <ul class="nav nav-tabs nav-justified mb-3 border-0" role="tablist">
        @foreach ($tabs as $tab)
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $tab['active'] ? 'active' : '' }}" data-bs-toggle="tab" role="tab"
                    href="#{{ $tab['id'] }}" aria-selected="{{ $tab['active'] ? 'true' : 'false' }}">
                    <i class="{{ $tab['icon'] }} mx-2 fs-22"></i> </br>
                    {{ $tab['label'] }}
                </a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content">
        @foreach ($tabs as $tab)
            <div class="tab-pane text-muted {{ $tab['active'] ? 'active show' : '' }}"" id="{{ $tab['id'] }}"
                role="tabpanel">
                @include($tab['page'])
            </div>
        @endforeach
    </div>
</div>
