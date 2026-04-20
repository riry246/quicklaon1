<div class="row">
    <div class="col-xl-11">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">
                    Transaction Details
                </div>
            </div>
            <div class="card-body">
                @foreach ($ls->transaction as $t)
                    <div class="row">
                        <div class="col-xl-12">
                            <div class=" p-3 mb-5 bg-success-transparent rounded">
                                <div class="row">
                                    <div class="col-xl-3">
                                        <div>
                                            <p class="card-text mb-0 fs-14 fw-semibold">Transaction Number
                                            </p>
                                            <div class="card-title text-muted fs-5 fw-bold mb-0">
                                                #{{ $t->transaction_id }}</div>
                                        </div>
                                    </div>
                                    <div class="col-xl-9">
                                        <div>
                                            <p class="card-text mb-0 fs-14 fw-semibold">Status Description</p>
                                            <div class="card-title text-muted fs-12 mb-0 fs-5 fw-bold">
                                                {{ $t->status_description }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-xl-4">
                                        <div>
                                            <p class="card-text mb-0 fs-14 fw-semibold">Bank Detail
                                            </p>
                                            <div class="card-title text-muted fs-12 mb-0">{{ $t->account_name }}</div>
                                            <div class="card-title text-muted fs-12 mb-0">{{ $t->account_bsb }}</div>
                                            <div class="card-title text-muted fs-12 mb-0">{{ $t->account_number }}</div>
                                            <div class="card-title text-muted fs-12 mb-0">{{ $t->institution }}</div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div>
                                            <p class="card-text mb-0 fs-14 fw-semibold">Date</p>
                                            <div class="card-title text-muted fs-12 mb-0">{{ $t->created_at }}</div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div>
                                            <p class="card-text mb-0 fs-14 fw-semibold">Status
                                            </p>
                                            <div class="card-title text-muted fs-12 mb-0">{{ $t->status }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="shadow-none p-3 mt-3 mb-3 bg-light rounded">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div>
                                                <p class="card-text mb-0 fs-14 fw-semibold">Description
                                                </p>
                                                <div class="card-title text-muted mb-0">{{ $t->description }}</div>
                                                <a href="{{ route('transaction.view', $t->id) }}"
                                                class="btn btn-primary btn-sm btn-wave mt-3">View Detail</a>
                                            </div>
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
</div>
