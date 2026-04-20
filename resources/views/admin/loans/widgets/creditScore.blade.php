<div class="row">
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">Credit Score</div>
        </div>
        <div class="card-body">
            <div class="d-flex align-items-center w-100">
                <div class="me-2">
                    <span class="avatar avatar-rounded">
                        <img src="https://i.pinimg.com/originals/71/d4/62/71d4623ea086c2621a68e3df385c451d.png"
                            alt="img">
                    </span>
                </div>
                <div class="">
                    <div class="fs-15 fw-semibold">Credit Score and Report</div>
                    <div class="fs-1 fw-bold">
                        {{ isset($latestcreditScore->score_value) ? $latestcreditScore->score_value : 0 }}
                    </div>
                    <div class="fs-15 fw-semibold">out of 1000</div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="btn-list">
                    <div class="d-grid gap-2 mb-4">
                        <a href="{{ route('credit.score.generate', $loanapplication['id']) }}"
                            class="btn btn-primary btn-wave" type="button">Update Credit
                            Score</a>
                        @if (isset($latestcreditScore->score_value))
                            <a href="{{ route('credit.score.latest', $loanapplication['id']) }}"
                                class="btn btn-warning btn-wave" type="button">View
                                Report</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="d-flex justify-content-between">
                @if (isset($latestcreditScore->created_at))
                    <div class="fs-semibold fs-14">{{ $loan_helper->formateDate($latestcreditScore->created_at) }}</div>
                    <div class="fw-semibold text-success">Updated on</div>
                @endif

            </div>
        </div>
    </div>
</div>
