 @php
     $frequency = 1;
 @endphp
 @foreach ($loanstatement as $ls)
     @php
         $frequency = $ls['frequency'];
     @endphp
 @endforeach

 <div class="d-flex flex-wrap p-3 border-bottom border-block-end-dashed">
     <div class="me-3">
         <span>
             Application ID:
         </span>
         <p class="fw-semibold mb-0 text-dark">#<u> <a target="_blank"
                     href="{{ route('loan.view', $loanapplication->id) }}">{{ $loanapplication->id }}</a></u></p>
     </div>
     <div class="d-flex flex-wrap justify-content-sm-evenly flex-fill">
         <div class="m-sm-0 m-2"> <span>Payment Frequency</span>
             <p class="fw-semibold mb-0">{{ ucfirst($frequency) }}</p>
         </div>
         <div class="m-sm-0 m-2"> <span>Total Paid</span>
             <p class="text-success fw-semibold mb-0">{{ $loan_helper->getStatementTotalByStatus($loanapplication->id,['Complete'])}}</p>
         </div>
         <div class="m-sm-0 m-2"> <span>Outstanding</span>
             <p class="fw-semibold mb-0">{{ $loan_helper->getStatementTotalByStatus($loanapplication->id,['Scheduled', 'Re-scheduled', 'Hold'])}}</p>
         </div>
         <div class="m-sm-0 m-2"> <span>Pending</span>
             <p class="text-warning fw-semibold mb-0">{{ $loan_helper->getStatementTotalByStatus($loanapplication->id,['WaitingOnClearedFunds'])}}</p>
         </div>
         <div class="m-sm-0 m-2"> <span>Dishourned</span>
             <p class="text-danger fw-semibold mb-0">{{ $loan_helper->getStatementTotalByStatus($loanapplication->id,['Dishonoured'])}}</p>
         </div>

     </div>
 </div>
