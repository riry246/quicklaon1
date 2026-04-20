@if (isset($statements->income->regular))
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between border-bottom-1 px-0">
                <div class="card-title">
                    <i class="bi bi-graph-down-arrow mx-2"></i> Expenses
                    @if (count($statements->expenses->payments) < 1)
                        - NO REGULAR EXPENSES FOUND
                    @endif
                </div>
                <a href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseExample5"
                    aria-expanded="true" aria-controls="collapseExample5" class="">
                    <i class="ri-arrow-down-s-line fs-18 collapse-open"></i>
                    <i class="ri-arrow-up-s-line collapse-close fs-18"></i>
                </a>
            </div>
            @if (count($statements->expenses->payments) > 1)
                <div class="collapse show" id="collapseExample5" style="">
                    <div class="card-body px-0">
                        <div class="table-responsive ">
                            <table class="table table-search text-nowrap table-expenses mb-4">
                                <thead>
                                    <tr>
                                        <th>Expenses</th>
                                        <th>%</th>
                                        <th>Avg P.M</th>
                                        <th>Total</th>
                                        @php
                                            $fromMonth = new DateTime('2023-01');
                                            $toMonth = new DateTime('2024-01');
                                            $currentMonth = clone $fromMonth;
                                            $date = [];
                                        @endphp

                                        @while ($currentMonth <= $toMonth)
                                            <th>{{ $currentMonth->format('Y-m') }}</th>
                                            @php
                                                $date[] = $currentMonth->format('Y-m');
                                                $currentMonth->add(new DateInterval('P1M'));
                                            @endphp
                                        @endwhile
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $percentage = 0;
                                        $avgMonthly = 0;
                                        $paymentTotal = 0;

                                    @endphp

                                    @foreach ($date as $d)
                                        @php
                                            $totaldaywise[$d] = 0;
                                        @endphp
                                    @endforeach
                                    @forelse ($statements->expenses->payments as $expenses)
                                        @php
                                            $total = 0;
                                            $percentage = $expenses->percentageTotal + $percentage;
                                            $avgMonthly = abs($expenses->avgMonthly) + $avgMonthly;

                                        @endphp
                                        <tr style="background: #101011">
                                            <td class="fw-bold fs-6">{{ ucfirst($expenses->division) }}</td>
                                            <td class="fw-bold fs-6">{{ ucfirst($expenses->percentageTotal) }}</td>
                                            <td class="fw-bold fs-6">
                                                {{ $loan_helper->formatCurrencyabs($expenses->avgMonthly) }}</td>
                                            @foreach ($expenses->subCategory as $sub)
                                                @foreach ($sub->changeHistory as $c)
                                                    @php
                                                        $total = $total + abs($c->amount);
                                                        $paymentTotal = $paymentTotal + abs($c->amount);
                                                    @endphp
                                                @endforeach
                                            @endforeach
                                            <td class="fw-bold fs-6">{{ $loan_helper->formatCurrencyabs($total) }}</td>
                                            <td colspan="13"></td>

                                        </tr>

                                        @foreach ($expenses->subCategory as $sub)
                                            <tr>
                                                <td class="ps-5">-
                                                    {{ ucfirst($sub->category->expenseClass->classTitle) }}</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>

                                                @foreach ($date as $d)
                                                    @php
                                                        $amount = null;
                                                        $daywise[$d] = 0;
                                                    @endphp

                                                    @foreach ($sub->changeHistory as $c)
                                                        @if ($d == $c->date)
                                                            @php
                                                                $amount = abs($c->amount);
                                                            @endphp
                                                        @endif
                                                    @endforeach

                                                    @if ($amount)
                                                        <td>{{ $loan_helper->formatCurrencyabs($amount) }}</td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                        @endforeach

                                    @empty
                                    @endforelse

                                    <tr class="totalrow">
                                        <td class="fw-bold fs-6">Payment Total</td>
                                        <td class="fw-bold fs-6">{{ $percentage }}</td>
                                        <td class="fw-bold fs-6">{{ $loan_helper->formatCurrencyabs($avgMonthly) }}</td>
                                        <td class="fw-bold fs-6">{{ $loan_helper->formatCurrencyabs($paymentTotal) }}</td>
                                        <td colspan="14"></td>

                                    </tr>
                                     @if(isset($statements->expenses->bankFees))
                                    <tr>
                                        <td class="fw-bold fs-6">Bank Fee Total</td>
                                        <td class="fw-bold fs-6"></td>
                                        <td class="fw-bold fs-6">
                                            {{ $loan_helper->formatCurrencyabs(abs($statements->expenses->bankFees->avgMonthly)) }}
                                        </td>
                                        @php
                                            $total = 0;
                                            $avgMonthly = $avgMonthly + abs($statements->expenses->bankFees->avgMonthly);

                                        @endphp
                                        @foreach ($statements->expenses->bankFees->changeHistory as $c)
                                            @php
                                                $total = $total + abs($c->amount);
                                                $paymentTotal = $paymentTotal + abs($c->amount);

                                            @endphp
                                        @endforeach
                                        <td class="fw-bold fs-6">{{ $loan_helper->formatCurrencyabs($total) }}</td>



                                        @foreach ($date as $d)
                                            @php
                                                $amount = null;
                                                $daywise[$d] = 0;
                                            @endphp

                                            @foreach ($statements->expenses->bankFees->changeHistory as $c)
                                                @if ($d == $c->date)
                                                    @php
                                                        $amount = abs($c->amount);
                                                    @endphp
                                                @endif
                                            @endforeach

                                            @if ($amount)
                                                <td>{{ $loan_helper->formatCurrencyabs($amount) }}</td>
                                            @else
                                                <td></td>
                                            @endif
                                        @endforeach
                                    </tr>
                                    @endif
                                    @if(isset($statements->expenses->loanRepayments))
                                    <tr>
                                        <td class="fw-bold fs-6">Loan Repayment Total</td>
                                        <td class="fw-bold fs-6"></td>
                                        <td class="fw-bold fs-6">
                                            {{ $loan_helper->formatCurrencyabs(abs($statements->expenses->loanRepayments->avgMonthly)) ?? 'N/A' }}
                                        </td>
                                        @php
                                            $total = 0;
                                            $avgMonthly = $avgMonthly + abs($statements->expenses->loanRepayments->avgMonthly);
                                        @endphp
                                        @foreach ($statements->expenses->loanRepayments->changeHistory as $c)
                                            @php
                                                $total = $total + abs($c->amount);
                                                $paymentTotal = $paymentTotal + abs($c->amount);
                                            @endphp
                                        @endforeach
                                        <td class="fw-bold fs-6">{{ $loan_helper->formatCurrencyabs($total) }}</td>



                                        @foreach ($date as $d)
                                            @php
                                                $amount = null;
                                                $daywise[$d] = 0;
                                            @endphp

                                            @foreach ($statements->expenses->loanRepayments->changeHistory as $c)
                                                @if ($d == $c->date)
                                                    @php
                                                        $amount = abs($c->amount);
                                                    @endphp
                                                @endif
                                            @endforeach

                                            @if ($amount)
                                                <td>{{ $loan_helper->formatCurrencyabs($amount) }}</td>
                                            @else
                                                <td></td>
                                            @endif
                                        @endforeach
                                    </tr>
                                    @endif
                                    @if(isset($statements->expenses->cashWithdrawals))
                                    <tr>
                                        <td class="fw-bold fs-6">Cash Withdraw Total</td>
                                        <td class="fw-bold fs-6"></td>
                                        <td class="fw-bold fs-6">
                                            {{ $loan_helper->formatCurrencyabs(abs($statements->expenses->cashWithdrawals->avgMonthly)) ?? 'N/A' }}
                                        </td>
                                        @php
                                            $total = 0;
                                            $avgMonthly = $avgMonthly + abs($statements->expenses->cashWithdrawals->avgMonthly);
                                        @endphp
                                        @foreach ($statements->expenses->cashWithdrawals->changeHistory as $c)
                                            @php
                                                $total = $total + abs($c->amount);
                                                $paymentTotal = $paymentTotal + abs($c->amount);
                                            @endphp
                                        @endforeach
                                        <td class="fw-bold fs-6">{{ $loan_helper->formatCurrencyabs($total) }}</td>



                                        @foreach ($date as $d)
                                            @php
                                                $amount = null;
                                                $daywise[$d] = 0;
                                            @endphp

                                            @foreach ($statements->expenses->cashWithdrawals->changeHistory as $c)
                                                @if ($d == $c->date)
                                                    @php
                                                        $amount = abs($c->amount);
                                                    @endphp
                                                @endif
                                            @endforeach

                                            @if ($amount)
                                                <td>{{ $loan_helper->formatCurrencyabs($amount) }}</td>
                                            @else
                                                <td></td>
                                            @endif
                                        @endforeach
                                    </tr>
                                    @endif
                                     @if(isset($statements->expenses->externalTransfers))
                                    <tr>
                                        <td class="fw-bold fs-6">External Total</td>
                                        <td class="fw-bold fs-6"></td>
                                        <td class="fw-bold fs-6">
                                            {{ $loan_helper->formatCurrencyabs(abs($statements->expenses->externalTransfers->avgMonthly)) ?? 'N/A'  }}
                                        </td>
                                        @php
                                            $total = 0;
                                            $avgMonthly = $avgMonthly + abs($statements->expenses->externalTransfers->avgMonthly);
                                        @endphp
                                        @foreach ($statements->expenses->externalTransfers->changeHistory as $c)
                                            @php
                                                $total = $total + abs($c->amount);
                                                $paymentTotal = $paymentTotal + abs($c->amount);
                                            @endphp
                                        @endforeach
                                        <td class="fw-bold fs-6">{{ $loan_helper->formatCurrencyabs($total) }}</td>



                                        @foreach ($date as $d)
                                            @php
                                                $amount = null;
                                                $daywise[$d] = 0;
                                            @endphp

                                            @foreach ($statements->expenses->externalTransfers->changeHistory as $c)
                                                @if ($d == $c->date)
                                                    @php
                                                        $amount = abs($c->amount);
                                                    @endphp
                                                @endif
                                            @endforeach

                                            @if ($amount)
                                                <td>{{ $loan_helper->formatCurrencyabs($amount) }}</td>
                                            @else
                                                <td></td>
                                            @endif
                                        @endforeach
                                    </tr>
                                    @endif
                                    <tr class="totalrow">
                                        <td class="fw-bold fs-6">Grand Total</td>
                                        <td class="fw-bold fs-6"></td>
                                        <td class="fw-bold fs-6">{{ $loan_helper->formatCurrencyabs($avgMonthly) }}</td>
                                        <td class="fw-bold fs-6">{{ $loan_helper->formatCurrencyabs($paymentTotal) }}</td>
                                        <td colspan="14"></td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif
