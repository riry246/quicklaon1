 @include('admin.loans.widgets.illion.itrsScore')
 <div>
     <h4 class="mb-3">Account Summary</h4>
     <table class="table  text-nowrap mt-4 mb-4">
         <thead>
             <tr>
                 <th>SN</th>
                 <th>Account Details</th>
                 <th>Account Name</th>
                 <th>Available Balance</th>
                 <th>Current Balance</th>
                 <th>Total Debits</th>
                 <th>Total Credits</th>
             </tr>
         </thead>
         <tbody>
             @php
                 $available = 0;
                 $balance = 0;
                 $totalDebits = 0;
                 $totalCredits = 0;
             @endphp
             @foreach ($illionBankAccount as $key => $account)
                 @php
                     $statementSummary = json_decode($account->statementSummary);
                     $available += $account->available;
                     $balance += $account->balance;
                     $totalDebits += $statementSummary->totalDebits;
                     $totalCredits += $statementSummary->totalCredits;
                 @endphp
                 <tr>
                     <td>{{ ++$key }}</td>
                     <td style="width:200px; text-wrap:wrap;">{{ $account->account_holder }}<br />{{ $account->bsb }} {{ $account->account_number }}</td>
                     <td>{{ $account->name }}</td>
                     <td>{{ $loan_helper->formatCurrency($account->available) }}</td>
                     <td>{{ $loan_helper->formatCurrency($account->balance) }}</td>
                     <td>{{ $loan_helper->formatCurrency($statementSummary->totalDebits) }}</td>
                     <td>{{ $loan_helper->formatCurrency($statementSummary->totalCredits) }}</td>
                 </tr>
             @endforeach
             <tr>
                 <td colspan="3" class="fw-bold">Total</td>
                 <td class="fw-bold">{{ $loan_helper->formatCurrency($available) }}</td>
                 <td class="fw-bold">{{ $loan_helper->formatCurrency($balance) }}</td>
                 <td class="fw-bold">{{ $loan_helper->formatCurrency($totalDebits) }}</td>
                 <td class="fw-bold">{{ $loan_helper->formatCurrency($totalCredits) }}</td>
             </tr>
         </tbody>
     </table>

     @include('admin.loans.widgets.illion.decisionMetrics')
 </div>
