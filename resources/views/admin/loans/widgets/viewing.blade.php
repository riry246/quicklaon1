<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title"> Loan Application Viewing Status </div>
            </div>
            <div class="card-body">
                <div class="hstack gap-3"> This loan application is currently being viewed by <b>"{{ $loan_helper->getUserName($loanapplication->viewed_by_user_id) }}"</b>
                    <button type="button" class="btn btn-primary" onclick="clearViewingStatus()">Take Over</button>
                    <div class="vr"></div>
                    <a href="{{ route('home') }}" class="btn btn-outline-danger">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="loadingIndicator" class="spinner-border text-primary d-none" role="status">
    <span class="visually-hidden">Loading...</span>
</div>

<script>
    const loanApplicationId = '{{ $loanapplication->id }}';

    function clearViewingStatus() {
        // Show loading indicator
        document.getElementById('loadingIndicator').classList.remove('d-none');

        fetch(`{{ route('loan-application.clear-view-status', $loanapplication->id) }}`, {
            method: 'GET',
        }).then(() => {
            // Hide loading indicator after request is completed
            document.getElementById('loadingIndicator').classList.add('d-none');

            location.reload(); // Reload the page after clearing viewing status
        });
    }
</script>
