@if(isset($leadmarket))
<div class="row">
    <div class="card custom-card">
        <div class="card-header justify-content-between">
            <div class="card-title">Lead Market</div>
            <a href="{{ route('leadmarket.view',$leadmarket->id )}}"class="btn btn-sm btn-primary">View Detail</a>
           
        </div>
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col-xl-12 mt-3">
                    <p class="fs-15 mb-2 me-4 fw-semibold">Lead ID: <span>{{ $leadmarket->lead_id }}</span>
                    </p>
                </div>
                <div class="col-xl-12 mt-2">
                    <p class="fs-15 mb-2 me-4 fw-semibold">Token: {{ $leadmarket->lead_token  }} </p>
                </div>
                <div class="col-xl-12 mt-2">
                    <p class="fs-15 mb-2 me-4 fw-semibold">Status:
                        {{ $leadmarket->status }} </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
