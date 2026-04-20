
@if(isset($illionCustomerInfo->scoreModels))
@php
    $scoreModels = json_decode($illionCustomerInfo->scoreModels);
@endphp

@foreach ($scoreModels as $score)
    <div class="col-xl-12 mt-3">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card crm-highlight-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="fw-semibold fs-18 text-fixed-white mb-2">{{ $score->modelName }} </div>
                                <div class="fs-1 fw-bold">
                                     {{ $score->modelScore ?? 'N/A'}}
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endif