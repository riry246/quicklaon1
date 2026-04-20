<div class="card custom-card">
    <div class="card-header">
        <div class="card-title">Monoova Bank Account</div>
    </div>
    <div class="card-body">
        @if (isset($user->monoovaAccount))
            <div class="row">
                <div class="col-xl-12 mt-3">
                    <p class="fs-15 mb-2 me-4 fw-semibold">Account Name: {{ $user->monoovaAccount->bankAccountName }}
                    </p>
                </div>
                <div class="col-xl-12 mt-2">
                    <p class="fs-15 mb-2 me-4 fw-semibold">BSB: {{ $user->monoovaAccount->bsb }} </p>
                </div>
                <div class="col-xl-12 mt-2">
                    <p class="fs-15 mb-2 me-4 fw-semibold">Account Number:
                        {{ $user->monoovaAccount->bankAccountNumber }} </p>
                </div>
                <div class="col-xl-12 mt-2">
                    <p class="fs-15 mb-2 me-4 fw-semibold">PayID: {{ $user->monoovaAccount->payid }} </p>
                </div>
            </div>

    </div>
    <div class="card-footer">
        <div class="d-flex justify-content-between">
            <div class="fs-semibold fs-14">

                {{ $user->monoovaAccount->created_at }}

            </div>
            <div class="fw-semibold text-success">

                Created at

            </div>
        </div>
    </div>
@else
    <div class="btn-list text-center mt-2">
        <a href="{{ route('payid.create', $user->id) }}"
            class="btn btn-success-light btn-w-lg btn-wave waves-effect waves-light">Generate Account</a>
    </div>
</div>
@endif

</div>
