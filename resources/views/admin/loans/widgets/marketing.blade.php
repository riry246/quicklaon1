<div class="row">
    <div class="card custom-card">
        <div class="card-header justify-content-between">
            <div class="card-title">Messages</div>
            <a href="{{ route('message.loan.view',$loanapplication->id) }}"class="btn btn-sm btn-primary">View All</a>
        </div>
        <div class="card-body">
            <div class="btn-list mt-2">
                <div class="d-grid gap-2 mb-4 mt-2 mx-auto">
                    <div class="d-flex align-items-center  justify-content-between">

                        <div class=" text-center">
                            <div class="fs-15 fw-semibold">SMS</div>
                            <div class="fs-1 fw-bold">{{ count($sms) }}</div>                         
                        </div>
                        <div class=" text-center">
                            <div class="fs-15 fw-semibold">Emails</div>
                            <div class="fs-1 fw-bold">{{ count($email) }}</div> 
                        </div>
                        <div class=" text-center">
                            <div class="fs-15 fw-semibold">In App</div>
                            <div class="fs-1 fw-bold">{{ count($inapp) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
