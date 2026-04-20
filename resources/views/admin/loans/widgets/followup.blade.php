@inject('loan_helper', 'App\Http\Helpers\LoanHelper')

<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">
                    Follow up
                </div>
                <div class="prism-toggle">
                    <button class="btn btn-primary btn-wave" data-bs-toggle="modal" data-bs-target="#ticket">Create
                        Ticket<i class="bx bx-plus-circle ms-2 d-inline-block align-middle"></i></button>
                   
                </div>
            </div>
            <div class="card-body">
                @if (count($loanFollowUps) > 0)
                    @foreach ($loanFollowUps as $lf)
                        <div class="row">
                            <div class="col-xl-12">
                                <div class=" p-3 mb-5 bg-success-transparent rounded">
                                    <div class="row">
                                        <div class="col-xl-3">
                                            <div>
                                                <p class="card-text mb-0 fs-14 fw-semibold">Case Number
                                                </p>
                                                <div class="card-title text-muted fs-5 fw-bold mb-0">
                                                    #{{ $lf->case_number }}</div>
                                            </div>
                                        </div>
                                        <div class="col-xl-7">
                                            <div>
                                                <p class="card-text mb-0 fs-14 fw-semibold">Topic</p>
                                                <div class="card-title text-muted fs-12 mb-0 fs-5 fw-bold">
                                                    {{ $lf->topic }}</div>
                                            </div>

                                        </div>
                                        <div class="col-xl-2">
                                            <a target="_blank" href="{{ route('case.view', $lf->id) }}"
                                                class="btn btn-secondary btn-wave mt-3">View Detail</a>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-xl-2">
                                            <div>
                                                <p class="card-text mb-0 fs-14 fw-semibold">Created by
                                                </p>
                                                @php
                                                    $user = $loan_helper->getUser($lf->created_by);
                                                @endphp
                                                <div class="card-title text-muted fs-12 mb-0">{{ $user->first_name }}
                                                    {{ $user->last_name }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-2">
                                            <div>
                                                <p class="card-text mb-0 fs-14 fw-semibold">Assigned to
                                                </p>
                                                <div class="card-title text-muted fs-12 mb-0">Jhoan Joe
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-2">
                                            <div>
                                                <p class="card-text mb-0 fs-14 fw-semibold">Date</p>
                                                <div class="card-title text-muted fs-12 mb-0">21 Aug,
                                                    2023 10:34 am</div>
                                            </div>
                                        </div>
                                        <div class="col-xl-2">
                                            <div>
                                                <p class="card-text mb-0 fs-14 fw-semibold">Priorities
                                                </p>
                                                <div class="card-title text-muted fs-12 mb-0">{{ $lf->priority }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-2">
                                            <div>
                                                <p class="card-text mb-0 fs-14 fw-semibold">Status
                                                </p>
                                                <div class="card-title text-muted fs-12 mb-0">{{ $lf->status }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-2">
                                            <div>
                                                <p class="card-text mb-0 fs-14 fw-semibold">Methods
                                                </p>
                                                <div class="card-title text-muted fs-12 mb-0">
                                                    {{ $lf->method ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 mt-2">
                                            <div>
                                                <p class="card-text mb-0 fs-14 fw-semibold">Next follow
                                                    up
                                                </p>
                                                <div class="card-title text-muted fs-12 mb-0">
                                                    {{$loan_helper->formateDateTime($lf->next_follow_up) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row  p-2">
                                        <div class="col-xl-12 mt-4 bg-success-transparent rounded p-3">
                                            <div>
                                                <p class="card-text mb-0 fs-14 fw-semibold">Case Detail:
                                                </p>
                                                <div class="card-title text-muted mb-0 mt-2">{!! $lf->description !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="row">
                        <div class="col-xl-12">
                            <p>No Case has been registerd yet</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
