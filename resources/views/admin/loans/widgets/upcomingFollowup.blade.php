 @if ($loanLatestFollowUps)
<div class="row">
    <div class="card custom-card text-center">
        <div class="card-header">
            <div class="card-title">Follow up</div>
        </div>
        <div class="card-body">
            <p class="card-text">Next Follow up</p>
            <h6 class="card-title fw-semibold">
                @php
                    $formattedDateTime = $loan_helper->formateDateTime($loanLatestFollowUps->next_follow_up);
                @endphp
                {{ $formattedDateTime ?? 'Not Available' }}

            </h6>
           
                <div class="btn-list mt-2">
                    <div class="d-grid gap-2 mb-4 mt-4">
                        <a target="_blank" href="{{ route('case.view', $loanLatestFollowUps->id) }}"
                            class="btn btn-secondary btn-wave mt-3">View Detail</a>
                    </div>
                </div>
          
        </div>
    </div>
</div>
@endif