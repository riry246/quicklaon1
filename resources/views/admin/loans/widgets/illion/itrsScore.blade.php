
@if($illionCustomerInfo->scoreModels)
@php
    $scoreModels = json_decode($illionCustomerInfo->scoreModels);
@endphp

@foreach ($scoreModels as $score)
    <div class="col-xl-12 mt-3">
        <div class="row">
            <div class="col-xl-8">
                <div class="card custom-card crm-highlight-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="fw-semibold fs-18 text-fixed-white mb-2">{{ $score->modelName }} </div>
                                <div class="fs-1 fw-bold">
                                    {{ $score->modelScore ?? 'N/A'}}
                                </div>
                            </div>
                            <div>
                                <div class="fw-semibold fs-18 text-fixed-white mb-2">Score Key Influencing Factors
                                    (KIFs):</div>
                                <ul class="list-unstyled crm-top-deals mb-0">
                                    @foreach ($score->keyInfluencingFactors as $i)
                                        <li>
                                            <div class="d-flex align-items-top flex-wrap">
                                                <div class="flex-fill">
                                                    <p class="fw-semibold mb-0">{{ $i->factorName }}</p>
                                                </div>
                                                <div class="fw-semibold fs-15">{{ $i->factorValue }}</div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endif