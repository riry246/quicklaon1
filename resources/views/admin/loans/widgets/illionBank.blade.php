<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Linked Bank Account</div>
            </div>
            <div class="card-body">

                @if ($illionPrimaryAccount)
                    <div class="d-flex align-items-center w-100">
                        <div class="me-2">

                        </div>
                                <div class="">
                                    <div class="fs-15 fw-semibold">{{ $illionPrimaryAccount->institution }}</div>
                                </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12 mt-3">
                                <p class="fs-15 mb-2 me-4 fw-semibold">Account Holder Name:
                                    {{ $illionPrimaryAccount->account_holder }}
                                </p>
                            </div>
                            <div class="col-xl-12 mt-3">
                                <p class="fs-15 mb-2 me-4 fw-semibold">Account Name: {{ $illionPrimaryAccount->name }}
                                </p>
                            </div>
                            <div class="col-xl-12 mt-3">
                                <p class="fs-15 mb-2 me-4 fw-semibold">BSB: {{ $illionPrimaryAccount->bsb }} </p>
                            </div>
                            <div class="col-xl-12 mt-3">
                                <p class="fs-15 mb-2 me-4 fw-semibold">Account Number:
                                    {{ $illionPrimaryAccount->account_number }} </p>
                            </div>
                        </div>
                @endif

                <div class="btn-list text-center mt-2">
                    <a href="{{ route('illion.customer.data', ['userid' => $user->id, 'id' => $loanapplication->id]) }}"
                        class="btn btn-success-light btn-w-lg btn-wave waves-effect waves-light">Generate or Update Bank
                        Statement</a>
                </div>
            </div>
        </div>
    </div>
</div>
