@if ($loanapplication->status == 'incomplete')
@endif


<div class="row">
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">Request Information</div>
        </div>

        @if ($loanapplication->status == 'processing' || $loanapplication->status == 'active')
            <div class="card-body">
                <div class="btn-list">
                    <div class="d-grid gap-2 mb-4">
                        <button class="btn btn-primary btn-wave" data-bs-toggle="modal"
                            data-bs-target="#request-information">Request Information</button>
                    </div>
                    @if ($loanapplication['approved_amount'] > 0 && $loanapplication->status == 'processing')
                        <div class="d-grid gap-2 mb-4">
                            <button class="btn btn-primary btn-wave" data-bs-toggle="modal"
                                data-bs-target="#contract-signing">Send Contract Signing</button>
                        </div>
                    @endif
                </div>
            </div>
            <div class="modal fade" id="request-information" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title">Request Information</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body pt-4">
                            <div class="col-xl-12">

                                <nav class="nav nav-style-1 nav-pills mb-3 nav-justified d-sm-flex d-block"
                                    role="tablist">
                                    <a class="nav-link active" data-bs-toggle="tab" role="tab" aria-current="page"
                                        href="#document" aria-selected="true">Document</a>
                                    <a class="nav-link " data-bs-toggle="tab" role="tab" aria-current="page"
                                        href="#request-id-verification" aria-selected="true">Identity</a>
                                    <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page"
                                        href="#request-mobile" aria-selected="true">Mobile</a>
                                    <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page"
                                        href="#request-email" aria-selected="true">Email</a>
                                    <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page"
                                        href="#request-payment" aria-selected="true">Payment</a>
                                </nav>
                                <div class="tab-content">

                                    <div class="tab-pane text-muted active show  " id="document" role="tabpanel">
                                        <form action="{{ route('loan.requestDocument', $loanapplication['id']) }}"
                                            method="post">
                                            @csrf
                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                                <p class="mb-2 text-muted">Document:</p>
                                                <select class="form-control js-example-basic-multiple" id="name"
                                                    data-trigger multiple name="document_type[]">
                                                    @foreach ($documentType as $document)
                                                        <option name="{{ $document['name'] }}">
                                                            {{ $document['name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-wave mt-3">Request
                                                Document</button>
                                        </form>
                                    </div>
                                    <div class="tab-pane text-muted  " id="request-id-verification" role="tabpanel">
                                        <form action="{{ route('loan.requestId', $loanapplication['id']) }}"
                                            method="post">
                                            @csrf
                                            <p>Request ID verification </p>
                                            <button type="submit" class="btn btn-primary btn-wave mt-3">Request
                                                ID Verification</button>
                                        </form>
                                    </div>
                                    <div class="tab-pane text-muted " id="request-mobile" role="tabpanel">
                                        <form action="{{ route('loan.requestMobile', $loanapplication['id']) }}"
                                            method="post">
                                            @csrf
                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                                <p class="mb-2 text-muted">Mobile:</p>
                                                <input type="text" class="form-control" id="input" name="mobile"
                                                    value="{{ $user['mobile'] }}">
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-wave mt-3">Request Mobile
                                                Verification</button>
                                        </form>
                                    </div>
                                    <div class="tab-pane text-muted " id="request-email" role="tabpanel">
                                        <form action="{{ route('loan.requestEmail', $loanapplication['id']) }}"
                                            method="post">
                                            @csrf
                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                                <p class="mb-2 text-muted">Email:</p>
                                                <input type="text" class="form-control" id="input"
                                                    name="email" value="{{ $user['email'] }}">
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-wave mt-3">Request Email
                                                Verification</button>
                                        </form>
                                    </div>
                                    <div class="tab-pane text-muted" id="request-payment" role="tabpanel">
                                        @if (isset($bank->bank_name))
                                            <div class="d-flex align-items-center w-100">
                                                <div class="me-2">

                                                    <img src="{{ asset('assets/' . $bank->img) }}"" alt="img"
                                                        width="100">

                                                </div>
                                                <div class="">
                                                    <div class="fs-15 fw-semibold">{{ $bank->bank_name }}</div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-12 mt-3">
                                                    <p class="fs-15 mb-2 me-4 fw-semibold">Account Name:
                                                        {{ $bank->account_info }}
                                                    </p>
                                                </div>
                                                <div class="col-xl-12 mt-2">
                                                    <p class="fs-15 mb-2 me-4 fw-semibold">Account Number:
                                                        {{ $bank->primary_account }} </p>
                                                </div>
                                            </div>
                                            <div class="fw-semibold text-success">
                                                @if ($user['bank_verified'])
                                                    Verified
                                                @else
                                                    Not Verified
                                                @endif
                                            </div>
                                        @endif
                                        <form action="{{ route('loan.bankrequest', $loanapplication['id']) }}"
                                            method="post">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-wave mt-3">Request Bank
                                                Verification</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="contract-signing" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <form action="{{ route('contract.create', $loanapplication['id']) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title">Send Contract</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body px-4">
                                <p>Are you sure? You want to send contract?</p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary">Send</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @elseif($loanapplication->status == 'incomplete')
            <div class="card-body">
                <div class="btn-list">
                    <div class="d-grid gap-2 mb-4">
                        <p>Send application completion reminder</p>
                        <a href="{{ route('loan.reminder', $loanapplication->id) }}"
                            class="btn btn-primary btn-wave">Send Request</a>
                    </div>
                </div>
            </div>
        @else
            <div class="card-body">
                <p>Not Available</p>
            </div>

        @endif
    </div>
</div>
