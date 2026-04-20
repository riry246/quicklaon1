@inject('loan_helper', 'App\Http\Helpers\LoanHelper')
<div
    style="page-break-before: always;
        background: url('https://cashfaster.com.au/images/certificate.png'); background-size: 100%; padding:30px">
    <h2>Signature Certificate</h2>
    <h4>Reference number: {{ $contract->ref_number }}</h4>

    <table>
        <tbody>
            <tr>
                <td width="150">Signer</td>
                <td width="150">Timestamp</td>
                <td width="150">Signature</td>
            </tr>
            <tr>
                <td colspan="3">
                    <hr />
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <h3>{{ $borrower->first_name . ' ' . $borrower->middle_name . ' ' . $borrower->last_name }}
                        <span style="font-size:13px; font-weight:normal; display:block">Email:
                            {{ $borrower->email }}</span>
                        <span style="font-size:13px; font-weight:normal; display:block">Shared via link</span>
                    </h3>
                </td>
                <td rowspan="2">
                    <p style="border:1px solid #000; padding:10px; width:180px">{!! $signature_customer !!}</p>
                    <span style="font-size:13px; font-weight:normal; display:block">IP address:
                        {{ $contract->ip_address_customer }}</span>

                </td>
            </tr>
            <tr>
                <td>
                    <span style="font-size:13px; font-weight:normal; display:block">Sent:</span>
                    <span style="font-size:13px; font-weight:normal; display:block">Viewed:</span>
                    <span style="font-size:13px; font-weight:normal; display:block">Signed:</span>
                </td>
                <td>
                    <span
                        style="font-size:13px; font-weight:normal; display:block">{{ $loan_helper->formateDateTime($contract->created_at) }}</span>
                    <span
                        style="font-size:13px; font-weight:normal; display:block">{{ $loan_helper->formateDateTime($contract->view_date_customer) }}</span>
                    <span
                        style="font-size:13px; font-weight:normal; display:block">{{ $loan_helper->formateDateTime($contract->signed_date_customer) }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <hr />
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <h3>Cash Faster Australia
                        <span style="font-size:13px; font-weight:normal; display:block">Email:
                            contract@cashfaster.com.au</span>

                    </h3>
                </td>
                <td rowspan="2">
                    <p style="border:1px solid #000; padding:10px; width:180px">{!! $signature_cf !!}</p>
                    <span style="font-size:13px; font-weight:normal; display:block">IP address:
                        {{ $contract->ip_address_cf }}</span>

                </td>
            </tr>
            <tr>
                <td>
                    <span style="font-size:13px; font-weight:normal; display:block">Sent:</span>
                    <span style="font-size:13px; font-weight:normal; display:block">Viewed:</span>
                    <span style="font-size:13px; font-weight:normal; display:block">Signed:</span>
                </td>
                <td>
                    <span
                        style="font-size:13px; font-weight:normal; display:block">{{ $loan_helper->formateDateTime($contract->created_at) }}</span>
                    <span
                        style="font-size:13px; font-weight:normal; display:block">{{ $loan_helper->formateDateTime($contract->view_date_cf) }}</span>
                    <span
                        style="font-size:13px; font-weight:normal; display:block">{{ $loan_helper->formateDateTime($contract->signed_date_cf) }}</span>
                </td>
            </tr>
        </tbody>
        </tbody>
    </table>

    <p>Document completed by all parties on:<br> {{ $loan_helper->formateDateTime($contract->updated_at) }} </p>
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <table>
        <tr>
            <td><img src="https://cashfaster.com.au/images/logo-small.png" width="100" /></td>
            <td>
                <p style="font-size: 8pt; margin-top: 10px;">

                    Signed with CashFaster<br> CashFaster is a document workflow and
                    certified eSignature solution trusted by 40,000+ companies worldwide.
                </p>
                <td />
</div>
