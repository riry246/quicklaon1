<div class="card custom-card">
    <div class="card-body text-dark">
        <div class="d-flex w-100">
            <div class="d-flex align-items-center justify-content-between w-100 flex-wrap">
                <div class="row align-items-center justify-content-between">
                    <div class="col-11">
                        <div class="row">
                            <div class="col-12">
                                <h4 class="">{{ $statements->title }}</h4>
                            </div>
                            <div class="col-12">
                                <p class="fw-normal fs-16 mb-0"><i
                                        class="ri-calendar-line me-2"></i>{{ $statements->filters[0]->value }} to
                                    {{ $statements->filters[1]->value }}</p>
                            </div>
                            <div class="col-12">
                                <p class="fw-normal fs-16 mb-0"><b>ID: </b>{{ $statements->id }}</p>
                            </div>
                            <div class="col-12">
                                <p class="fw-normal fs-16 mb-0"><b>Date:
                                    </b>{{ $loan_helper->formateDateTime($statements->createdDate) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-1">
                        <div class="dropdown" style="float:right"> <a aria-label="anchor" href="javascript:void(0);"
                                class="btn btn-icon btn-sm btn-light" data-bs-toggle="dropdown"> <i
                                    class="fe fe-more-vertical"></i> </a>
                            
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
