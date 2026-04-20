 <tr>
     <td>{{ $i }}</td>
     <td>
         <p>{{ $l->description }}</p>
     </td>
     <td>#{{ $l->transaction_id }}</td>
     <td>
     @if($l->application_id)
     #<a target="_blank" href="{{ route('loan.view',$l->application_id);}}"><u>{{ $l->application_id }}</u></a>
     @else
     N/A
     @endif
     </td>
     <td>
         @if ($l->type == 'Credit')
             <span class="text-success">+
                 ${{ $l->amount }}</span>
         @endif
     </td>
     <td>
         @if ($l->type == 'Debit')
             <span class="text-danger">- ${{ $l->amount }}</span>
         @endif
     </td>
     <td>
         @if ($l->status == 'Complete')
             <span class="badge bg-success-transparent">{{ $l->status }}</span>
         @elseif($l->status == 'WaitingOnClearedFunds')
             <span class="badge bg-warning-transparent">Waiting</span>
         @elseif($l->status == 'Dishonoured')
             <span class="badge bg-danger-transparent">{{ $l->status }}</span>
         @endif
     </td>
     <td>
         <p>{{ $l->status_description }}</p>
     </td>
     <td>Entered:<br/>{{ $loan_helper->formateDateTime($l->created_at) }}<br/>
     Effective:<br/>{{ $loan_helper->formateDateTime($l->updated_at) }}</td>

     <td>
         <div class="mb-md-0 mb-2">
             <a target="_blank" href="{{ route('transaction.view', $l->id) }}"
                 class="btn btn-icon btn-success-transparent rounded-pill btn-wave" title="Edit">
                 <i class="ri-eye-line"></i>
             </a>
         </div>
     </td>

 </tr>
