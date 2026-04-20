<div class="row">
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">Risk Assessment Information</div>
        </div>
        <div class="card-body">
            <div class="btn-list">
                <div class="d-grid gap-2 mb-4">
                    @if ($loanapplication->user->risk_flag == 1)
                        <button class="btn btn-danger btn-wave" data-bs-toggle="modal"
                            data-bs-target="#request-information">Duplication User Information Found</button>
                    @elseif($loanapplication->user->risk_flag == 2)
                        <button class="btn btn-danger btn-wave" data-bs-toggle="modal"
                            data-bs-target="#request-information">Tried to verify already existed ID</button>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
