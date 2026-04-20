<div class="col-xxl-12 col-xl-12 ">
    <div class="card custom-card recent-transactions-card overflow-hidden">
        <div class="card-header justify-content-between">
            <div class="card-title"><i class="bi bi-clipboard2-data text-secondary "></i> &nbsp Recent Settlements</div>
            <div class="dropdown">
                <a aria-label="anchor" href="javascript:void(0);" class="btn btn-icon btn-sm btn-light"
                    data-bs-toggle="dropdown">
                    <i class="fe fe-more-vertical"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('transaction.index','all')}}">View All</a></li>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card-body p-0 ">
            <div class="list-group">
            @if (count($dashboard_helper->getLatestTransaction()) > 0)
                @foreach ($dashboard_helper->getLatestTransaction() as $t)
                    <a href="{{ route('transaction.view',$t->id)}}" class="border-0">
                        <div class="list-group-item border-0">
                            <div class="d-flex align-items-start">
                                <div class="w-100">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div class="mt-0">
                                            <p class="mb-0 fw-semibold"><span class="me-3">
                                                    {!! strlen($t->description) > 30 ? substr($t->description, 0, 30) . '...' : $t->description !!}
                                                </span></p>
                                            <span class="mb-0 fs-12 text-muted">{{ $t->type }}</span>
                                        </div>
                                        <div class="text-muted fs-12 text-center"></div>
                                        <span class="ms-auto text-nowrap">
                                            @if ($t->type == 'Debit')
                                                <span class="text-end text-danger d-block text-nowrap">
                                                    -$ {{ $t->amount }}
                                                </span>
                                            @else
                                                <span class="text-end text-success d-block text-nowrap">
                                                   + $ {{ $t->amount }}
                                                </span>
                                            @endif
                                            <span class="text-end text-muted d-block fs-12">
                                                {{ $loan_helper->convertDateTimeToRelativeFormat($t->created_at) }}</span>
                                        </span>
                                        </span>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach

@else
<p class="text-center m-5 p-5"> No data found</p>
@endif
            </div>
        </div>
    </div>
</div>
