<div class="modal fade" id="revert" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Revert Transaction</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4">
                <form action="{{ route('wallet.process') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card custom-card">
                                <div class="card-header">
                                    <div>
                                        <div class="mb-1">Amount</div>
                                        <div class="fs-20 fw-semibold">
                                            {{ $loan_helper->formatCurrency($transaction->amount) }}</div>
                                    </div>

                                </div>
                                <div class="card-body">

                                    @php
                                        $bsb = substr($transaction->bsb, 0, 6);
                                        $accountNumber = substr($transaction->account_number, 6);
                                    @endphp
                                    <div class="row mx-2">
                                        <div>
                                            <div class="fs-14 py-2"><span class="fw-semibold text-dark">Account
                                                    Name:</span><span
                                                    class="text-dark fw-semibold float-end">{{strstr($transaction->account_name, '(', true) ?: $transaction->account_name }}</span>
                                            </div>
                                            <div class="fs-14 py-2"><span
                                                    class="fw-semibold text-dark">Institution:</span><span
                                                    class="text-dark fw-semibold float-end">{{ $transaction->institution ?? 'N/A' }}</span>
                                            </div>
                                            <div class="fs-14 py-2"><span class="fw-semibold text-dark">BSB:</span><span
                                                    class="text-dark fw-semibold float-end">{{ $bsb ?? 'N/A' }}</span>
                                            </div>
                                            <div class="fs-14 py-2"><span class="fw-semibold text-dark">Account
                                                    Number:</span><span
                                                    class="text-dark fw-semibold float-end">{{ $accountNumber ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                        <label class="fw-semibold fs-12 mt-4 mb-2">Transaction Codes:</label>

                                        <div class="row mx-2">
                                            <input type="hidden" name="bsb" value="{{ $bsb }}" />
                                            <input type="hidden" name="accountNo" value="{{ $accountNumber }}" />
                                            <input type="hidden" name="institution"
                                                value="{{ $transaction->institution }}" />
                                            <input type="hidden" name="accountName"
                                                value="{{strstr($transaction->account_name, '(', true) ?: $transaction->account_name }}" />
                                            <input type="hidden" name="amount" value="{{ $transaction->amount }}" />
                                            <input type="hidden" name="application_id"
                                                value="{{ $transaction->application_id }}" />

                                            @php
                                                $remarks = '';
                                                $method = '';

                                                if ($transaction->type == 'Credit') {
                                                    $remarks = 'CFRefund#'.$transaction->transaction_id;
                                                    $method = 'send';
                                                } else {
                                                    $remarks = 'CFReverse#'.$transaction->transaction_id;
                                                    $method = 'deposit';
                                                }
                                            @endphp

                                            <input type="hidden" name="remarks" value="{{ $remarks }}" />
                                            <input type="hidden" name="method" value="{{ $method }}" />



                                            <input type="text" name="otp[]" class="form-control mb-2"
                                                id="input" required maxlength="4" placeholder="Code 1">
                                            @if ($errors->has('otp'))
                                                <span class="error_msg"
                                                    role="alert">{{ $errors->first('otp') }}</span>
                                            @endif
                                            <input type="text" name="otp[]" class="form-control" id="input"
                                                required maxlength="4" placeholder="Code 2">
                                            @if ($errors->has('otp'))
                                                <span class="error_msg"
                                                    role="alert">{{ $errors->first('otp') }}</span>
                                            @endif


                                        </div>
                                        <div class="row mx-2">
                                            <button type="button" id="sendTransacationCode"
                                                class="mt-4 btn btn-primary btn-wave btn-lg waves-effect waves-light">Generate
                                                Transaction Code</button>
                                            <button type="submit"
                                                class="mt-4 btn btn-success btn-wave btn-lg waves-effect waves-light">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
