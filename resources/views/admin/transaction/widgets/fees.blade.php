<div class="col-xl-12">
    <div class="card custom-card">
        <div class="card-header justify-content-between border-bottom-1">
            <div class="card-title">
                Transaction Fees
            </div>
            <a href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="true"
                aria-controls="collapseExample" class="">
                <i class="ri-arrow-down-s-line fs-18 collapse-open"></i>
                <i class="ri-arrow-up-s-line collapse-close fs-18"></i>
            </a>
        </div>
        <div class="collapse show" id="collapseExample" style="">
            <div class="card-body">
                <table class="table table-search text-nowrap mt-4 mb-4 mb-4">
                    <thead>
                        <tr>
                            <th>fee_amount_excluding_gst</th>
                            <th>fee_amount_gst_component</th>
                            <th>fee_amount_including_gst</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td>$ {{ $transaction->fee_amount_excluding_gst }}</td>
                            <td>$ {{ $transaction->fee_amount_gst_component }}</td>
                            <td>$ {{ $transaction->fee_amount_including_gst }}</td>
                            <td>$
                                {{ $transaction->fee_amount_excluding_gst + $transaction->fee_amount_gst_component + $transaction->fee_amount_including_gst }}
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
