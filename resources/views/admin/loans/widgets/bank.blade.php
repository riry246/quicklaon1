<div class="row">
    @include('admin.loans.widgets.illion.accountList')



    @if (isset($bank->bank_name))
        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Linked Bank Account</div>
                </div>
                <div class="card-body">
                    @if (isset($bank->bank_name))
                        <div class="d-flex align-items-center w-100">
                            <div class="me-2">

                                <img src="{{ asset('assets/' . $bank->img) }}"" alt="img" width="100">

                            </div>
                            <div class="">
                                <div class="fs-15 fw-semibold">{{ $bank->bank_name }}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12 mt-3">
                                <p class="fs-15 mb-2 me-4 fw-semibold">Account Name: {{ $bank->account_info }} </p>
                            </div>
                            @php
                                $bsb = substr($bank->primary_account, 0, 6);
                                $bsb = substr_replace($bsb, '-', 3, 0);
                                $accountNumber = substr($bank->primary_account, 6);

                            @endphp
                            <div class="col-xl-12 mt-2">
                                <p class="fs-15 mb-2 me-4 fw-semibold">BSB: {{ $bsb }} </p>
                            </div>
                            <div class="col-xl-12 mt-2">
                                <p class="fs-15 mb-2 me-4 fw-semibold">Account Number: {{ $accountNumber }} </p>
                            </div>
                            <div class="col-xl-12 mt-2">
                                <p class="fs-15 mb-2 me-4 fw-semibold">Verified at : @if ($user['bank_verified'])
                                        {{ $bank->verified_at }}
                                    @else
                                        Not Verified
                                    @endif
                                </p>
                            </div>
                        </div>

                </div>
                <div class="card-footer">
                    <div class="d-flex  text-center">
                        @if ($user->basiq_user_id)
                            <div class="btn-list  mx-auto mt-2">
                                <a target="_blank" href="{{ route('bankStatement', $user->basiq_user_id) }}"
                                    class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"
                                    data-bs-toggle="tooltip" data-bs-custom-class="tooltip-primary"
                                    data-bs-placement="top" data-bs-original-title="View Statement"
                                    aria-describedby="tooltip216447">
                                    <i class="ri-eye-line"></i> </a>
                                <a target="_blank"
                                    href="{{ route('bankStatement.affordability', $user->basiq_user_id) }}"
                                    class="btn btn-icon btn-outline-danger rounded-pill btn-wave waves-effect waves-light"
                                    data-bs-toggle="tooltip" data-bs-custom-class="tooltip-primary"
                                    data-bs-placement="top" data-bs-original-title="Affordability Statement"
                                    aria-describedby="tooltip216447">
                                    <i class="ri-bar-chart-2-line"></i> </a>
                                <a target="_blank" href="{{ route('bankStatement.consumer', $user->basiq_user_id) }}"
                                    class="btn btn-icon btn-outline-warning rounded-pill btn-wave waves-effect waves-light"
                                    data-bs-toggle="tooltip" data-bs-custom-class="tooltip-primary"
                                    data-bs-placement="top"
                                    data-bs-original-title="Consumer
                            Affordability Report"
                                    aria-describedby="tooltip216447">
                                    <i class="ri-bank-line"></i> </a>
                                <button
                                    class="btn btn-icon btn-outline-teal rounded-pill btn-wave waves-effect waves-light"
                                    data-bs-toggle="modal" data-bs-target="#SetPrimaryBank"
                                    data-bs-custom-class="tooltip-primary" data-bs-placement="top"
                                    data-bs-original-title="Set Primary Bank" title="Set Primary Bank"
                                    aria-describedby="tooltip216440">
                                    <i class="ri-edit-line"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="col-xl-6">
            <p>Not Available</p>
        </div>
    @endif
    @endif

    <div class="col-xl-6">
        @include('admin.loans.widgets.monoova')
    </div>
</div>
<!-- Start:: Set Primary Account -->
<div class="modal fade" id="SetPrimaryBank" tabindex="-1" aria-hidden="true">
    @if (isset($bank->bank_name))
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('bank.change.primary') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Change Primary Account</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4">
                        <div class="row gy-3">
                            <div class="col-xl-12" style="width: 500px">
                                <input type="hidden" name="bank_account_id" value="{{ $bank->id }}" />
                                <label>Accounts</label>
                                <select class="form-control mt-2" name="primary_account" id="choices-multiple-groups">
                                    <option value="">Choose a Account</option>
                                    @foreach ($accountList as $account)
                                        @if ($account['account_no'] == $bank->primary_account)
                                            <option value="{{ $account['account_no'] }}" selected>
                                                {{ $account['account_no'] }} -
                                                {{ $account['name'] }}</option>
                                        @else
                                            <option value="{{ $account['account_no'] }}">{{ $account['account_no'] }}
                                                -
                                                {{ $account['name'] }}</option>
                                        @endif
                                    @endforeach
                                </select>

                            </div>
                            <div class="col-xl-12 pt-5">
                                <div class="custom-toggle-switch d-flex align-items-center mb-4">
                                    <input id="toggleswitchSuccess" name="onlyfromPrimary" type="checkbox"
                                        @if ($bank->onlyfromPrimary == 1) checked @endif>
                                    <label for="toggleswitchSuccess" class="label-success"></label><span
                                        class="ms-3">Withdraw only from Primary Account</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger">Update</button>
                    </div>
                </div>
            </form>
        </div>
    @endif
</div>
