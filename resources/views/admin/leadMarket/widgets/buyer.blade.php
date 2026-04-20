@inject('loan_helper', 'App\Http\Helpers\LoanHelper')
<div class="card custom-card">
    <div class="card-header justify-content-between">
        <div class="card-title ">Buyer Detail
        </div>
    </div>
    <div class="card-body">

        <div class="d-flex w-100 border-bottom border-block-end-dashed">
            <div class="d-flex align-items-center justify-content-between w-100 flex-wrap mb-3">
                @foreach ($detail['Data']['Lead']['Buyer_Data'] as $k => $v)
                    <div class=" mt-3 col-3">
                        <p class="text-muted mb-0">{{ ucfirst(str_replace('_', ' ', $k)) }}</p>
                       @if ($k == 'CS_App_ID')
                            <p class="fw-normal"><a target="_blank"
                                    href="https://creditsense.com.au/admin/dashboard/leads/?search-appid={{ $v }}">{{ $v }}
                                    <i class="ri-eye-line mx-2"></i></a>
                            </p>
                        @else
                            <p class="fw-normal">{{ $v }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

    </div>

</div>

</div>
