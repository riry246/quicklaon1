<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if (session('fileUrl'))
            window.open("{{ session('fileUrl') }}", '_blank');
        @endif
    });
</script>


@if (isset($illionBankAccount[0]))
    <div class="d-flex w-100 mb-4">
        <div class="d-flex align-items-center justify-content-between w-100 flex-wrap">
            <div class="me-3">

                <h4 class="my-3">
                    @php
                        $img = $loan_helper->getBankImage($illionCustomerInfo->institution);
                    @endphp
                    @if ($img)
                        <img src="{{ $img }}" width="100" />
                    @endif
                    {{ $illionCustomerInfo->institution }}
                </h4>
            </div>
            <div class="me-3">
                @if ($illionCustomerInfo->filename)
                    <a target="_blank" href="{{ Storage::url('public/bankstatement/' . $illionCustomerInfo->filename) }}"
                        class="btn btn-success-light btn-w-lg btn-wave waves-effect waves-light">View Bank
                        Statement</a>
                @elseif ($illionCustomerInfo->zipFile)
                    <a href="{{ route('illion.customer.html', $loanapplication->id) }}"
                        class="btn btn-success-light btn-w-lg btn-wave waves-effect waves-light">Generate Bank
                        Statement HTML</a>
                @endif
                <a href="{{ route('illion.customer.data', ['userid' => $user->id, 'id' => $loanapplication->id]) }}"
                    class="btn btn-primary-light btn-w-lg btn-wave waves-effect waves-light">Update Bank Statement</a>

                @if (isset($riskScore->filename))
                    <a target="_blank"
                        href="{{ Storage::url('public/bureau/' . $riskScore->filename) }}"
                        class="btn btn-success-light btn-w-lg btn-wave waves-effect waves-light">View Risk Report</a>
                @elseif ($illionCustomerInfo->zipFile)
                    <a href="{{ route('illion.credit.check', $loanapplication->id) }}"
                        class="btn btn-success-light btn-w-lg btn-wave waves-effect waves-light">Generate Risk
                        Report</a>
                @endif
            </div>
        </div>
    </div>
    @if ($illionCustomerInfo->scoreModels)
        @include('admin.loans.widgets.illion.maintab')
    @endif
@endif
