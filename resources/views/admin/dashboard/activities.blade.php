<div class="col-12">
    <div class="card custom-card">
        <div class="card-header justify-content-between">
            <div class="card-title"><i class="bi bi-activity text-secondary"></i> &nbsp  Activities</div>
            <div class="dropdown">
                <a href="javascript:void(0);" class="p-2 fs-12 text-muted" data-bs-toggle="dropdown">
                    View All
                </a>

            </div>
        </div>
        <div class="card-body mt-0 latest-timeline" id="latest-timeline">
            <ul class="timeline-main mb-0 list-unstyled">
                @foreach ($dashboard_helper->getLatestActivities() as $a)
                    <li>
                        <div class="featured_icon1 featured-danger"></div>
                    </li>
                    <li class="mt-0 activity">
                        <a href="javascript:void(0);" class="fs-12">
                            <p class="mb-0"><span class="fw-semibold">
                                    #{{ $loan_helper->getUserName($a->user_id) }}</span>
                                <span class="ms-2 fs-12">
                                    {!! strlen($a->description) > 30 ? substr($a->description, 0, 30) . '...' : $a->description !!}
                                </span>
                                </span>
                            </p>
                        </a>
                        <small
                            class="text-muted mt-0 mb-0 fs-10">{{ $loan_helper->convertDateTimeToRelativeFormat($a->created_at) }}</small>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
