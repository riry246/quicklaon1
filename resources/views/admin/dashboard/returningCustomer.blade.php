 <div class="col-xl-12">
     <div class="card custom-card mb-0">
         <div class="card-header justify-content-between">
             <div class="card-title">
                 Returning Customers
             </div>

         </div>
         <div class="card-body">
             <nav class="nav nav-style-1 nav-pills mb-4 nav-justified" role="tablist">
                 @foreach ($dashboard_helper->generateReturningCustomerReport() as $count => $statements)
                     <a class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab" role="tab"
                         href="#tabcutomer-{{ $count }}">
                         <span class="d-block mb-1">{{ $count }}</span>
                         <span class="d-block mb-0 op-7 fs-12">Applications</span>
                     </a>
                 @endforeach
             </nav>

             <div class="tab-content pt-3 my-3">
                 @foreach ($dashboard_helper->generateReturningCustomerReport() as $count => $statements)
                     <div class="tab-pane {{ $loop->first ? 'active' : '' }} border-0 p-0"
                         id="tabcutomer-{{ $count }}" role="tabpanel">
                         <div class="row">
                             <div class="col-md-12">
                                 <div class="card custom-card bg-light">
                                     <div class="card-header justify-content-between">
                                         <div class="card-title">{{ count($statements) }} Applicants
                                             ({{ $dashboard_helper->formateNumber((count($statements) / $dashboard_helper->countLoan('all')) * 100) }})
                                             % </div>
                                     </div>
                                     <div class="card-body">

                                         <div class="table-responsive">
                                             <table class="table table-search mb-4 mt-4">
                                                 <thead>
                                                     <tr>
                                                         <th>#</th>
                                                         <th>Customer Name</th>
                                                         <th>Action</th>
                                                     </tr>
                                                 </thead>
                                                 <tbody>
                                                     @foreach ($statements as $statement)
                                                         <tr>
                                                             <td>{{ $loop->iteration }}</td>
                                                             <td>{{ $loan_helper->getUserName($statement->user_id) }}
                                                             </td>
                                                             <td>
                                                                 <div class="mb-md-0 mb-2">
                                                                     <a target="_blank"
                                                                         href="{{ route('customer.view', $statement->user_id) }}"
                                                                         class="btn btn-icon btn-success-transparent rounded-pill btn-wave"
                                                                         title="View">
                                                                         <i class="ri-file-history-fill"></i>
                                                                     </a>
                                                                 </div>
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
