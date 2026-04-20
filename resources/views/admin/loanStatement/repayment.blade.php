@php
    // Generate a random session code
    $randomSessionCode = bin2hex(random_bytes(16)); // generates a 32-character random string

    if (!$user->basiq_user_id) {
        $user->basiq_user_id = $randomSessionCode;
    }
@endphp
<!-- Start:: Approve loan -->
<div class="modal fade" id="repayment-{{ $ls->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('loan.update.statement', ['userid' => $user->basiq_user_id, 'id' => $ls->id]) }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Re-Scheduled Statement #{{ $ls->id }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <div class="row gy-3 mt-2">

                        <input type="hidden" name="payment_status" value="Rescheduled&charge" />

                        <div class="col-xl-12" style="width: 500px">
                            <label>Settlement Date</label>
                            <div class="input-group mt-2">
                                <div class="input-group-text text-muted"> <i class="ri-calendar-line"></i> </div>
                                <input type="text" name="settlement_date" class="form-control" id="date"
                                    value="{{ $ls->settlement_date }}" placeholder="Choose date">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="update-{{ $ls->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('loan.update.statement', ['userid' => $user->basiq_user_id, 'id' => $ls->id]) }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Update Statement #{{ $ls->id }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <div class="row gy-3 mt-2">
                        <div class="col-xl-12 col-lg-12 col-md-6 col-sm-12">
                            <p class="mb-2 text-muted">Amount </p>
                            <input type="text" class="form-control" name="amount" value="{{ $ls->weekly_payment }}">
                        </div>
                        <div class="col-xl-12" style="width: 500px">
                            <label>Payment Status ({{ $ls->payment_status }})</label>
                            <select class="form-control mt-2" name="payment_status">
                                <option value="Complete" @if ($ls->payment_status == 'Complete') selected @endif>Completed
                                </option>
                                <option value="Hold" @if ($ls->payment_status == 'Hold') selected @endif>Hold
                                </option>
                                <option value="Canceled" @if ($ls->payment_status == 'Canceled') selected @endif>Canceled
                                </option>
                                <option value="WaitingOnClearedFunds" @if ($ls->payment_status == 'WaitingOnClearedFunds') selected @endif>
                                    WaitingOnClearedFunds</option>
                                <option value="Scheduled" @if ($ls->payment_status == 'Scheduled') selected @endif>Scheduled
                                </option>
                                <option value="Re-scheduled" @if ($ls->payment_status == 'Re-scheduled') selected @endif>
                                    Re-scheduled</option>
                                <option value="Dishonoured" @if ($ls->payment_status == 'Dishonoured') selected @endif>
                                    Dishonoured</option>
                                <option value="Dishonoured&Rescheduled"
                                    @if ($ls->payment_status == 'Dishonoured') selected @endif>
                                    Dishonoured & Reschedule</option>
                            </select>
                        </div>
                        <div class="col-xl-12" style="width: 500px">
                            <label>Settlement Date</label>
                            <div class="input-group mt-2">
                                <div class="input-group-text text-muted"> <i class="ri-calendar-line"></i> </div>
                                <input type="text" name="settlement_date" class="form-control" id="date"
                                    value="{{ $ls->settlement_date }}" placeholder="Choose date">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="partial-{{ $ls->id }}" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <form
            action="{{ route('loan.update.statement.partial', ['userid' => $user->basiq_user_id, 'id' => $ls->id]) }}"
            method="POST" enctype="multipart/form-data" id="partialAmountForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Partial Payment #{{ $ls->id }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <p class="mb-2 fw-bold">Max Amouunt: ${{ $ls->weekly_payment }}</p>
                    <div class="row gy-3 mt-2">
                        <div class="col-xl-12 col-lg-12 col-md-6 col-sm-12">
                            <p class="mb-2 text-muted">Partial Amount 1:</p>
                            <input type="text" class="form-control" name="amount1"
                                id="amount1{{ $ls->id }}">
                        </div>
                        <div class="col-xl-12" style="width: 500px">
                            <label>Settlement Date</label>
                            <div class="input-group mt-2">
                                <div class="input-group-text text-muted"> <i class="ri-calendar-line"></i> </div>
                                <input type="text" name="settlement_date_1" class="form-control" id="date"
                                    value="{{ $ls->settlement_date }}" placeholder="Choose date">
                            </div>
                        </div>
                    </div>
                    <div class="row gy-3 mt-2">
                        <div class="col-xl-12 col-lg-12 col-md-6 col-sm-12">
                            <p class="mb-2 text-muted">Partial Amount 2:</p>
                            <input type="text" class="form-control" name="amount2"
                                id="amount2{{ $ls->id }}">
                        </div>
                        <div class="col-xl-12" style="width: 500px">
                            <label>Settlement Date</label>
                            <div class="input-group mt-2">
                                <div class="input-group-text text-muted"> <i class="ri-calendar-line"></i> </div>
                                <input type="text" name="settlement_date_2" class="form-control" id="date"
                                    value="{{ $ls->settlement_date }}" placeholder="Choose date">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
