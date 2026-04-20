@php
    $tabs = [
        ['id' => 'All', 'label' => 'All'],
        ['id' => 'today', 'label' => "Today's"],
        ['id' => 'Debit', 'label' => 'Debit'],
        ['id' => 'Credit', 'label' => 'Credit'],
        ['id' => 'Complete', 'label' => 'Complete'],
        ['id' => 'WaitingOnClearedFunds', 'label' => 'Waiting On Cleared Funds'],
        ['id' => 'Dishonoured', 'label' => 'Dishonoured'],
    ];
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
                                aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $tab['label'] }}</a>
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
                    <div class="row">
                        <div class="col-xxl-12 col-xl-12">
                            <div class="card custom-card">
                                <div class="card-header">
                                    <div class="card-title">
                                        {{ $tab['label'] }} Transactions
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="delete-datatable" class="table table-search text-nowrap  mt-4 mb-4">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Description</th>
                                                    <th>Transaction ID</th>
                                                    <th>Loan ID</th>
                                                    <th>Credit</th>
                                                    <th>Debit</th>
                                                    <th>Status</th>
                                                    <th>Response</th>
                                                    <th>Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $i = 0; @endphp
                                                @foreach ($list as $l)
                                                    @if (
                                                        $tab['id'] == $l->status ||
                                                            $tab['id'] == 'All' ||
                                                            $tab['id'] == $l->type ||
                                                            ($tab['id'] == 'today' && $l->created_at->format('Y-m-d') == now()->format('Y-m-d')))
                                                        @php $i++ @endphp
                                                        @include('admin.userTransaction.widgets.transaction')
                                                    @endif
                                                @endforeach
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            @endforeach
        </div>
    </div>
</div>
