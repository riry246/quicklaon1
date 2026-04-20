<div class="row">
    @foreach ($accounts as $account)
        <div class="col-xl-4">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        {{ $account->name }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between gap-2">

                        <div>
                            <span class="d-block text-muted fs-12 fw-normal">Account Holder</span>
                            <span class="fw-semibold">{{ $account->accountHolder }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div>
                                <span class="d-block text-muted fs-12 fw-normal">Account Number</span>
                                <span class="fw-semibold">{{ $account->accountNo }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between gap-2 mt-3">
                        <div class="d-flex align-items-center gap-2">
                            <div>
                                <span class="d-block text-muted fs-12 fw-normal">Balance</span>
                                <span class="fw-semibold">{{ $account->currency }} {{ $account->balance }}</span>
                            </div>
                        </div>
                        <div>
                            <span class="d-block text-muted fs-12 fw-normal">Available Fund</span>
                            <span class="fw-semibold">{{ $account->currency }} {{ $account->availableFunds }}</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between gap-2 mt-3">
                        <div class="d-flex align-items-center gap-2">
                            <div>
                                <span class="d-block text-muted fs-12 fw-normal">Account Status</span>
                                <span class="fw-semibold">{{ ucfirst($account->status) }}</span>
                            </div>
                        </div>
                        @if ($account->creditLimit)
                            <div>
                                <span class="d-block text-muted fs-12 fw-normal">Credit Limit</span>
                                <span class="fw-semibold">{{ $account->currency }} {{ $account->creditLimit }}</span>
                            </div>
                        @endif
                    </div>
                    <p class="card-text mt-2"><small class="text-muted">Last updated :
                            {{ $account->lastUpdated }}</small>
                    </p>
                </div>
                <div class="card-footer">
                    <div class="btn-list text-center">
                        <a target="_blank"
                            href="{{ route('bankStatement.statement', ['id' => $basiq_user_id, 'accountID' => $account->id]) }}"
                            class="btn btn-success-light btn-w-lg btn-wave waves-effect waves-light">View Statement</a>
                    </div>

                </div>
            </div>
        </div>
    @endforeach
</div>
