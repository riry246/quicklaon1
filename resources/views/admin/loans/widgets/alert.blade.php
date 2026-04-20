 @if ($loanapplication->excessive_outstanding_flag == 1)
     <div class="alert alert-danger mt-3" role="alert"> This loan application has an excessive outstanding
         amount. Please review and take appropriate action.
     </div>
 @elseif ($loanapplication->is_bad_debt == 1)
     <div class="alert alert-danger mt-3" role="alert"> This loan application is marked as bad debt. Please review and
         take appropriate action.
     </div>
 @elseif ($loanapplication->in_default == 1)
     <div class="alert alert-danger mt-3" role="alert"> This loan application is in default. Please review and take
         appropriate action.
     </div>
 @endif
