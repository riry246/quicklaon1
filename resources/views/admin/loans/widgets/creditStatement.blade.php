<div class="row">

    <div class="col-xxl-4 col-xl-4">
        @include('admin.loans.widgets.creditScore')
    </div>
    <div class="col-xxl-8 col-xl-8">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">
                    Credit Score
                </div>
            </div>
            <div class="card-body">
                <div class="deleted-table table-responsive">
                    <table class="table table-search text-nowrap mt-4 mb-4 mb-4">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Score Value</th>
                                <th>Score Card Num</th>
                                <th>System Message Description</th>
                                <th>System Message Code</th>
                                <th>Created at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($creditScore as $cs)
                                <tr>

                                    <td>{{ $i++ }} </td>


                                    <td>{{ $cs['score_value'] }}</td>
                                    <td>{{ $cs['score_card_num'] }}</td>
                                    <td>{{ $cs['system_message_description'] }}</td>
                                    <td>{{ $cs['system_message_code'] }}</td>
                                    <td>{{ $loan_helper->formateDateTime($cs['created_at']) }}
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
