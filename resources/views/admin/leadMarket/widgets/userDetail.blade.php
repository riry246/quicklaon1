@inject('loan_helper', 'App\Http\Helpers\LoanHelper')
<div class="card custom-card">
    <div class="card-header justify-content-between">
        <div class="card-title ">Lead Market Information
        </div>
        <button class="btn btn-sm btn-success">{{ ucfirst($lead['status']) ?? 'N/A' }}</button>
    </div>
    <div class="card-body">
        <div class="d-flex w-100 border-bottom border-block-end-dashed">
            <div class="d-flex align-items-center justify-content-between w-100 flex-wrap mb-3">
                <div class="me-3"> <!-- Updated classes -->
                    <p class="text-muted mb-0">ID</p>
                    <p class="fw-normal">#{{ $lead['lead_id'] ?? 'N/A' }}</p>
                </div>
                <div class="me-3"> <!-- Updated classes -->
                    <p class="text-muted mb-0">Token</p>
                    <p class="fw-normal">{{ $lead['lead_token'] ?? $lead['token']  }}</p>
                </div>
                <div class="me-3"> <!-- Updated classes -->
                    <p class="text-muted mb-0">Application Amount</p>
                    <p class="fw-normal">$ {{ $detail['Data']['Lead']['Application']['Amount'] ?? 'N/A' }}</p>
                </div>
                <div class="me-3"> <!-- Updated classes -->
                    <p class="text-muted mb-0">Application Reason</p>
                    <p class="fw-normal">{{ $detail['Data']['Lead']['Application']['Reason'] ?? 'N/A' }}</p>
                </div>
                <div class="me-3"> <!-- Updated classes -->
                    <p class="text-muted mb-0">Application Type</p>
                    <p class="fw-normal">{{ $detail['Data']['Lead']['Application']['Type'] ?? 'N/A' }}</p>
                </div>
                <div class="me-3"> <!-- Updated classes -->
                    <p class="text-muted mb-0">Priority</p>
                    <p class="fw-normal">{{ $detail['Data']['Lead']['Application']['Priority_Feature'] ?? 'N/A' }}</p>
                </div>
                <div class="me-3"> <!-- Updated classes -->
                    <p class="text-muted mb-0">Created At</p>
                    <p class="fw-normal">{{ $loan_helper->formateDateTime($lead['created_at']) ?? 'N/A' }}</p>
                </div>


            </div>
        </div>

        <div
            class="d-flex align-items-center border-bottom border-block-end-dashed justify-content-between w-100 flex-wrap mt-3">
            <div class="me-3"> <!-- Updated classes -->
                <p class="text-muted mb-0">Name</p>
                <p class="fw-normal">
                    {{ $detail['Data']['Lead']['Applicant']['Person_Applicant']['Title'] ?? '' }}
                    {{ $detail['Data']['Lead']['Applicant']['Person_Applicant']['First_Name'] ?? 'N/A' }}
                    {{ $detail['Data']['Lead']['Applicant']['Person_Applicant']['Last_Name'] ?? 'N/A' }}
                </p>
            </div>
            <div class="me-3"> <!-- Updated classes -->
                <p class="text-muted mb-0">Date of Birth</p>
                <p class="fw-normal">
                    {{ $detail['Data']['Lead']['Applicant']['Person_Applicant']['Date_Of_Birth'] ?? 'N/A' }}</p>
            </div>
            @foreach ($detail['Data']['Lead']['Applicant']['Person_Applicant']['Contact_Methods']['Contact_Method'] as $c)
                <div class="me-3"> <!-- Updated classes -->
                    @if (isset($c['Contact_Other']))
                        <p class="text-muted mb-0">{{ $c['Contact_Other']['Contact_Type'] ?? 'N/A' }} </p>
                        <p class="fw-normal">
                            {{ $c['Contact_Other']['V_Contact_Value'] ?? 'N/A' }}</p>
                    @else
                        <p class="text-muted mb-0">Address</p>
                        <p class="fw-normal">
                            {{ $c['Address']['Address_Line_1'] ?? '' }}
                            {{ $c['Address']['Address_Line_2'] ?? '' }},
                            {{ $c['Address']['Suburb'] ?? '' }},
                            {{ $c['Address']['State'] ?? '' }}
                            {{ $c['Address']['Postcode'] ?? '' }}

                        </p>
                    @endif
                </div>
            @endforeach
        </div>
        <div
            class="d-flex align-items-center border-bottom border-block-end-dashed justify-content-between w-100 flex-wrap mt-3">
            <div class="me-3"> <!-- Updated classes -->
                <p class="text-muted mb-0">Marital Status</p>
                <p class="fw-normal">
                    {{ $detail['Data']['Lead']['Applicant']['Person_Applicant']['Marital_Status'] ?? 'N/A' }}</p>
            </div>
            <div class="me-3"> <!-- Updated classes -->
                <p class="text-muted mb-0">Can Provide Security</p>
                <p class="fw-normal">
                    {{ $detail['Data']['Lead']['Applicant']['Person_Applicant']['Can_Provide_Security'] ?? 'N/A' }}</p>
            </div>
            <div class="me-3"> <!-- Updated classes -->
                <p class="text-muted mb-0">Can Provide Guarantor</p>
                <p class="fw-normal">
                    {{ $detail['Data']['Lead']['Applicant']['Person_Applicant']['Can_Provide_Guarantor'] ?? 'N/A' }}
                </p>
            </div>
            <div class="me-3"> <!-- Updated classes -->
                <p class="text-muted mb-0">Citizenship Status</p>
                <p class="fw-normal">
                @if(isset($detail['Data']['Lead']['Applicant']['Person_Applicant']['Citizenship_Status']))
                    {{ ucfirst(str_replace('_', ' ', $detail['Data']['Lead']['Applicant']['Person_Applicant']['Citizenship_Status'] )) ?? 'N/A' }}
                @else
                N/A
                @endif    
                </p>
            </div>
            <div class="me-3"> <!-- Updated classes -->
                <p class="text-muted mb-0">Credit Rating</p>
                <p class="fw-normal">
                    {{ $detail['Data']['Lead']['Applicant']['Credit_Rating']['Self_Reported_Credit_Rating'] ?? 'N/A' }}
                </p>
            </div>
        </div>
    </div>
</div>
