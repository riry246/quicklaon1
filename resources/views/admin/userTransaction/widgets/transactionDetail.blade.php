<div class="card custom-card">
    <div class="card-header justify-content-between flex-wrap">
        <div class="card-title ">Transaction Detail #{{ $transaction->transaction_id }}
        </div>
        <div class="btn-group" role="group" aria-label="Basic example">
            <a href="{{ route('transaction.check', $transaction->id) }}"
                class="btn btn-primary btn-sm btn-wave">Check Payment Status</a>
        </div>
    </div>
    <div class="card-body">
        <div class="d-flex w-100 border-bottom border-block-end-dashed">
            <div class="d-flex align-items-center justify-content-between w-100 flex-wrap mb-3">
                <div class="me-3">
                    <p class="text-muted mb-0">Transaction ID</p>
                    <p class="fw-normal">#{{ $transaction->transaction_id }}</p>
                </div>
                <div class="me-3">
                    <p class="text-muted mb-0">Refrence ID</p>
                    <p class="fw-normal">{{ $transaction->caller_unique_reference }}</p>
                </div>
                <div class="me-3">
                    <p class="text-muted mb-0">Transaction Date</p>
                    <p class="fw-normal">{{ $loan_helper->formateDateTime($transaction->created_at) }}</p>
                </div>
                <div class="me-3">
                    <p class="text-muted mb-0">Updated At</p>
                    <p class="fw-normal">{{ $loan_helper->formateDateTime($transaction->updated_at) }}</p>
                </div>
            </div>
        </div>
        <div class="d-flex w-100 border-bottom border-block-end-dashed">
            <div class="d-flex align-items-center justify-content-between w-100 flex-wrap mt-3 mb-3">
                <div class="me-3">
                    <p class="text-muted mb-0">Application ID</p>
                    <p class="fw-normal"><a
                            href="{{ route('loan.view', $transaction->application_id) }}">#{{ $transaction->application_id }}</a>
                    </p>
                </div>
                <div class="me-3">
                    <p class="text-muted mb-0">Cutomer Name</p>
                    <p class="fw-normal">{{ $loan_helper->getUserName($transaction->user_id) }}</p>
                </div>
                <div class="me-3">
                    <p class="text-muted mb-0">Amount</p>
                    <p class="fw-normal">$ {{ $transaction->amount }}</p>
                </div>
                <div class="me-3">
                    <p class="text-muted mb-0">Status</p>
                    <p class="fw-normal">{{ $transaction->status }}</p>
                </div>
            </div>
        </div>
        <div class="d-flex w-100 border-bottom border-block-end-dashed">
            <div class="d-flex align-items-center justify-content-between w-100 flex-wrap mt-3 mb-3">
                <div class="me-3">
                    <p class="text-muted mb-0">Transaction Type</p>
                    <p class="fw-normal">{{ $transaction->type }}</p>
                </div>
                <div class="me-3">
                    <p class="text-muted mb-0">Description</p>
                    <p class="fw-normal">{{ $transaction->description }}</p>
                </div>
                <div class="me-3">
                    <p class="text-muted mb-0">API Response</p>
                    <p class="fw-normal">{{ $transaction->status_description }}</p>
                </div>

            </div>
        </div>
        <div class="d-flex w-100 border-bottom border-block-end-dashed">
            <div class="d-flex align-items-center justify-content-between w-100 flex-wrap mt-3 mb-3">
                <div class="me-3">
                    <p class="text-muted mb-0">Transaction Completed Date</p>
                    <p class="fw-normal">{{ $loan_helper->formateDate($transaction->completed_date) ?? 'N/A' }}</p>
                </div>
                <div class="me-3">
                    <p class="text-muted mb-0">Transaction Dishonored Date</p>
                    <p class="fw-normal">{{ $loan_helper->formateDate($transaction->dishonoured_date) ?? 'N/A' }}</p>
                </div>
                <div class="me-3">
                    <p class="text-muted mb-0">Expected Clearance Date For Funds</p>
                    <p class="fw-normal">{{ $loan_helper->formateDate($transaction->expected_clearance_date_for_funds) ?? 'N/A' }}</p>
                </div>
                <div class="me-3">
                    <p class="text-muted mb-0">Fund Cleared Date</p>
                    <p class="fw-normal">{{ $loan_helper->formateDate($transaction->fundsClearedDate) ?? 'N/A' }}</p>
                </div>
                <div class="me-3">
                    <p class="text-muted mb-0">Credit Card Payment Status</p>
                    <p class="fw-normal">{{ $transaction->credit_card_payment_status ?? 'N/A' }}</p>
                </div>
                

            </div>
        </div>
        <div class="mt-3">
        @if($transaction->type == 'Debit')
        
        <h6>Transferred from:</h6>
        @else
        <h6>Transferred to:</h6>
        @endif
        </div>
        <div class="d-flex w-100 border-bottom border-block-end-dashed">
         @php
                    $bsb = substr($transaction->bsb, 0, 6);
            $bsb = substr_replace($bsb, '-', 3, 0);
             $accountNumber = substr($transaction->account_number, 6);
                    @endphp
            <div class="d-flex align-items-center justify-content-between w-100 flex-wrap mt-3 mb-3">
                <div class="me-3">
                    <p class="text-muted mb-0">Institution</p>
                    <p class="fw-normal">{{ $transaction->institution ?? 'N/A' }}</p>
                </div>
                <div class="me-3">
                    <p class="text-muted mb-0">Account Name</p>
                    <p class="fw-normal">{{ $transaction->account_name ?? 'N/A' }}</p>
                </div>
                <div class="me-3">
                    <p class="text-muted mb-0">BSB Number</p>
                   
                    <p class="fw-normal">{{ $bsb ?? 'N/A' }}</p>
                </div>
                <div class="me-3">
                    <p class="text-muted mb-0">Account Number</p>
                    <p class="fw-normal">{{$accountNumber ?? 'N/A' }}</p>
                </div>
                
                

            </div>
        </div>
    </div>
</div>
