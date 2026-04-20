@php
    $itrs_score = 0;
    if (isset($illionCustomerInfo->scoreModels)) {
        $scoreModels = json_decode($illionCustomerInfo->scoreModels);
        foreach ($scoreModels as $score) {
            $itrs_score = $score->modelScore;
        }
    }

@endphp

<div class="row">
    <div class="col-xl-12 col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title"> Scores </div>
                <div class="dropdown">
                    <a aria-label="anchor" href="javascript:void(0);" class="btn btn-icon btn-sm btn-light"
                        data-bs-toggle="dropdown">
                        <i class="fe fe-more-vertical"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item"
                                href="{{ route('credit.score.generate', $loanapplication->id) }}">Update Credit Score</a>
                        </li>
                        <li><a class="dropdown-item"
                                href="{{ route('illion.credit.check', $loanapplication->id) }}">Update Risk Score</a>
                        </li>
                        <li><a class="dropdown-item"
                                href="{{ route('illion.customer.data', ['userid' => $user->id, 'id' => $loanapplication->id]) }}">Update
                                Itrs Score</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body p-0 overflow-hidden">
                <div class="leads-source-chart d-flex align-items-center justify-content-center">
                    <canvas id="leads-source" class="chartjs-chart w-100 p-4"></canvas>
                    <div class="lead-source-value">
                        <span class="d-block fs-14">Total</span>
                        <span class="d-block fs-25 fw-bold" id="total-score">0</span>
                    </div>
                </div>
                <div class="row row-cols-12 border-top border-bottom border-block-start-dashed">
                    <div class="col p-0">
                        <div class="ps-4 py-3 pe-3 text-center border-end border-inline-end-dashed">
                            <span class="text-muted fs-12 mb-1 crm-lead-legend mobile d-inline-block">Credit
                            </span>
                            <div><span class="fs-16 fw-semibold"
                                    id="credit-score">{{ isset($latestcreditScore->score_value) ? $latestcreditScore->score_value : 0 }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col p-0">
                        <div class="p-3 text-center border-end border-inline-end-dashed">
                            <span class="text-muted fs-12 mb-1 crm-lead-legend desktop d-inline-block">iTRS-ML
                            </span>
                            <div><span class="fs-16 fw-semibold" id="itrs-score">{{ $itrs_score }}</span></div>
                        </div>
                    </div>
                    <div class="col p-0">
                        <div class="p-3 text-center border-end border-inline-end-dashed">
                            <span class="text-muted fs-12 mb-1 crm-lead-legend laptop d-inline-block">Risk
                            </span>
                            <div><span class="fs-16 fw-semibold"
                                    id="risk-score">{{ isset($riskScore->score) ? $riskScore->score : 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
