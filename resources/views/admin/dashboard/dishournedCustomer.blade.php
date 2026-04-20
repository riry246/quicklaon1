 <div class="col-xl-12">
     <div class="card custom-card mb-0">
         <div class="card-header justify-content-between">
             <div class="card-title">
                 Defaulters
             </div>

         </div>
         <div class="card-body">
             <nav class="nav nav-style-1 nav-pills mb-4 nav-justified" role="tablist">
                 @foreach ($dashboard_helper->generateDishonoredStatementReport() as $count => $statements)
                     <a class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab" role="tab"
                         href="#tab-{{ $count }}">
                         <span class="d-block mb-1">{{ $count }}</span>
                         <span class="d-block mb-0 op-7 fs-12">Dishonored</span>
                     </a>
                 @endforeach
             </nav>

             <div class="tab-content pt-3 my-3">
                 @foreach ($dashboard_helper->generateDishonoredStatementReport() as $count => $statements)
                     <div class="tab-pane {{ $loop->first ? 'active' : '' }} border-0 p-0" id="tab-{{ $count }}"
                         role="tabpanel">
                         <div class="row">
                             <div class="col-md-12">
                                 <div class="card custom-card bg-light">
                                     <div class="card-header justify-content-between">
                                         <div class="card-title">{{ $count }} Dishonored Statements</div>
                                     </div>
                                     <div class="card-body">

                                         <div class="table-responsive">
                                             <table class="table table-search mb-4 mt-4">
                                                 <thead>
                                                     <tr>
                                                         <th>#</th>
                                                         <th>Loan Application ID</th>
                                                         <th>Customer Name</th>
                                                     </tr>
                                                 </thead>
                                                 <tbody>
                                                     @foreach ($statements as $statement)
                                                         <tr>
                                                             <td>{{ $loop->iteration }}</td>
                                                             <td>
                                                                 @if ($statement->loan_application_id)
                                                                     <u>#<a target="_blank"
                                                                             href="{{ route('loan.view', $statement->loan_application_id) }}">{{ $statement->loan_application_id }}</a></u>
                                                                 @endif
                                                             </td>
                                                             <td>{{ $loan_helper->getUserNameByAppID($statement->loan_application_id) }}
                                                             </td>
                                                         </tr>
                                                     @endforeach
                                                 </tbody>
                                             </table>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 @endforeach
             </div>

         </div>
     </div>
 </div>
