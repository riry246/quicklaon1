<div class="modal fade" id="assign" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('loan.assign', $loanapplication['id']) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Assign Application</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <div class="row gy-3">
                        <div class="col-xl-12" style="width: 500px">
                            <label>Assign To</label>
                            <select class="form-control mt-2" name="assigned_to" data-trigger>
                                @foreach ($loan_helper->getAdminUsers() as $admin)
                                    @if ($admin->id == $loanapplication->assign_to)
                                        <option value="{{ $admin->id }}" selected>
                                            {{ $admin->first_name }}</option>
                                    @else
                                        <option value="{{ $admin->id }}">
                                            {{ $admin->first_name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Assign</button>
                </div>
            </div>
        </form>
    </div>
</div>
