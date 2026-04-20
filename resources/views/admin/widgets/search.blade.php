 <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModal" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <form action="{{ route('search') }}" method="post" enctype="multipart/form-data"
                 class="row g-3 mt-0">
                 @csrf
                 <div class="modal-body">
                     <div class="input-group">
                         <a href="javascript:void(0);" class="input-group-text" id="Search-Grid"><i
                                 class="fe fe-search header-link-icon fs-18"></i></a>
                         <input type="search" class="form-control border-0 px-2" placeholder="Search" name="keyword"
                             aria-label="Username">
                     </div>
                 </div>
                 <div class="modal-footer">
                     <div class="btn-group ms-auto">
                         <button type="submit" class="btn btn-sm btn-primary">Search</button>
                     </div>
                 </div>
             </form>
         </div>
     </div>
 </div>
