@inject('loan_helper', 'App\Http\Helpers\LoanHelper')
<div class="card custom-card">
    <div class="card-header justify-content-between">
        <div class="card-title ">Employment History
        </div>
    </div>
    <div class="card-body">
    @foreach ($detail['Data']['Lead']['Applicant']['Employment_History']['Employment'] as $employment)
        <div class="d-flex w-100 border-bottom border-block-end-dashed">
            <div class="d-flex align-items-center justify-content-between w-100 flex-wrap mb-3">
                <div class="me-3">
                    <p class="text-muted mb-0">Organisation Name</p>
                    <p class="fw-normal">{{ $employment['Organisation_Name'] ?? 'N/A' }}</p>
                </div>

                <div class="me-3">
                    <p class="text-muted mb-0">Occupation</p>
                    <p class="fw-normal">{{ $employment['Occupation'] ?? 'N/A' }}</p>
                </div>

                <div class="me-3">
                    <p class="text-muted mb-0">Start Date</p>
                    <p class="fw-normal">{{ $employment['Start_Date'] ?? 'N/A' }}</p>
                </div>

                <div class="me-3">
                    <p class="text-muted mb-0">End Date</p>
                    <p class="fw-normal">{{ $employment['End_Date'] ?? 'N/A' }}</p>
                </div>

                <div class="me-3">
                    <p class="text-muted mb-0">Employment Type</p>
                    <p class="fw-normal">{{ ucfirst(str_replace('_', ' ', $employment['Employment_Type']))  ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    @endforeach
    </div>
</div>

</div>

</div>
