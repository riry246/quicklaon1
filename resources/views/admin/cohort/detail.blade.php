@extends('layouts.admin')
@section('content')
    @inject('loan_helper', 'App\Http\Helpers\LoanHelper')
    <div class="container-fluid">
        <!-- Page Header -->
        @include('admin.general.pageheader')
        <!-- Page Header Close -->
        <!-- Start:: row-5 -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Cohort Analysis for Bad Debts as on
                            ( {{ $loan_helper->formateDateDynamic($date['dateForm'], 'j M, Y') }} to
                            {{ $loan_helper->formateDateDynamic($date['dateTo'], 'j M, Y') }} )</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-4 mt-4" aria-describedby="file-export_info">
                                <thead>
                                    <tr>
                                        <th class="bg-primary">Particular</th>
                                        @foreach ($report as $k => $v)
                                            <th class="bg-primary">{{ $k }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $particular = [
                                            'cash_price_of_total_loans',
                                            'cash_price_of_total_active_loans',
                                            'total_client_of_that_cohort',
                                            'number_of_active_clients',
                                            'number_of_sureshot_bad_debts_clients',
                                            'accrued_book_value_of_sureshot_bad_debt_clients',
                                            'cash_price_value_of_sureshot_bad_debt_clients',
                                            'average_collection_rate',
                                            'percentage_of_sureshot_bad_debt_in_number',
                                            'percentage_of_sureshot_bad_debt_in_cash_price',
                                            'first_payment_dishonored',
                                            'percentage_of_dishonored_payments',
                                        ];
                                    @endphp
                                    @foreach ($particular as $p)
                                        <tr>
                                            <td class="bg-primary">{{ ucwords(str_replace('_', ' ', $p)) }}</td>
                                            @foreach ($report as $r)
                                                @foreach ($r as $k => $v)
                                                    @if ($k == $p)
                                                        @if ($v['type'] == 'money')
                                                            <td class="text-muted">
                                                                {{ $loan_helper->formatCurrency($v['value']) }}</td>
                                                        @elseif($v['type'] == 'percentage')
                                                            <td class="text-muted">{{ $v['value'] }} %</td>
                                                        @else
                                                            <td class="text-muted">{{ $v['value'] }}</td>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endforeach
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
@endsection
