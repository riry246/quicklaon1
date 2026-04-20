<div class="card-body">
    @php
        $tabs = [
            [
                'id' => 'service-ability',
                'label' => 'ServiceAbility Report',
                'icon' => 'bi bi-diagram-3',
                'active' => true,
                'page' => 'admin.loans.widgets.serviceAbility',
            ],
            [
                'id' => 'application-detail',
                'label' => 'Applicant Loan Detail',
                'icon' => 'bi bi-person',
                'active' => false,
                'page' => 'admin.loans.widgets.userAttr',
            ],
            [
                'id' => 'bank',
                'label' => 'Bank Information ',
                'icon' => 'bi bi-bank2',
                'active' => false,
                'page' => 'admin.loans.widgets.bank',
            ],
            [
                'id' => 'contract',
                'label' => 'Application Contract',
                'icon' => 'bi bi-file-earmark-text',
                'active' => false,
                'page' => 'admin.loans.widgets.contract',
            ],
            [
                'id' => 'document_tab',
                'label' => 'Additional Documents',
                'icon' => 'bi bi-file-earmark-diff',
                'active' => false,
                'page' => 'admin.loans.widgets.document',
            ],

            [
                'id' => 'statement',
                'label' => 'Application Statement',
                'icon' => 'bi bi-clipboard2-data',
                'active' => false,
                'page' => 'admin.loans.widgets.loanStatement',
            ],
            [
                'id' => 'credit',
                'label' => 'Applicant Credit Scores',
                'icon' => 'bi bi-globe2',
                'active' => false,
                'page' => 'admin.loans.widgets.creditStatement',
            ],
            [
                'id' => 'followup',
                'label' => 'Follow Up',
                'icon' => 'bi bi-headset',
                'active' => false,
                'page' => 'admin.loans.widgets.followup',
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
