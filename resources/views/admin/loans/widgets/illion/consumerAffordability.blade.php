@if (isset($affordabilty))


    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title"> Consumer Affortdability </div>

            </div>
            <div class="card-body">
                <div class="accordion accordion-solid-primary" id="accordionPrimarySolidExample">
                    @foreach ($affordabilty as $k => $a)
                        @php
                            $id = str_replace(' ', '', $k); // Remove white spaces from the ID
                        @endphp
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $id }}">
                                <button class="accordion-button @if (!$loop->first) collapsed @endif"
                                    type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $id }}" aria-expanded="false"
                                    aria-controls="collapse{{ $id }}">
                                    {{ $k }}
                                </button>
                            </h2>
                            <div id="collapse{{ $id }}"
                                class="accordion-collapse collapse @if ($loop->first) show @endif"
                                aria-labelledby="heading{{ $id }}"
                                data-bs-parent="#accordionPrimarySolidExample">
                                <div class="accordion-body">
                                    @foreach ($a as $item)
                                        @foreach ($item['analysisCategory']['transactionGroups'] as $t)
                                            <div class="col-xl-12">
                                                <div class="card custom-card">
                                                    <div class="card-header">
                                                        <div class="card-title">{{ $t['name'] }}</div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row gy-md-0 gy-3">
                                                            @foreach ($t['analysisPoints'] as $a)
                                                                <div
                                                                    class="col-xxl-3 col-xl-3 col-lg-3 col-md-3 col-sm-6 mb-3">
                                                                    <div class="d-flex align-items-top">
                                                                        <div> <span
                                                                                class="d-block mb-1 text-muted">{{ $loan_helper->beautifyVariableName($a['name']) }}</span>
                                                                            @if ($a['type'] == 'money')
                                                                                <h6 class="fw-semibold mb-0">
                                                                                    {{ $loan_helper->formatCurrency($a['value']) }}
                                                                                </h6>
                                                                            @elseif($a['type'] == 'date')
                                                                                <h6 class="fw-semibold mb-0">
                                                                                    {{ $loan_helper->formateDate($a['value']) }}
                                                                                </h6>
                                                                            @else
                                                                                <h6 class="fw-semibold mb-0">
                                                                                    {{ $a['value'] }}</h6>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        @include(
                                                                    'admin.loans.widgets.illion.metricTransaction',
                                                                    $t)
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
@endif
