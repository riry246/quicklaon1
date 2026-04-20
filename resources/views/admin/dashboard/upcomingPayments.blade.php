<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card mb-0">
            <div class="card-header justify-content-between">
                <div class="card-title">
                    Upcoming Payments
                </div>

            </div>
            <div class="card-body pb-0">
                @php
                    $today = strtotime('today');
                    //$today = strtotime('-1 day', strtotime('today'));
                    $numDays = 7;
                    $dates = [];

                    for ($i = 0; $i < $numDays; $i++) {
                        $date = date('Y-m-d', strtotime("+$i days", $today));
                        $dates[] = $date;
                    }
                @endphp

                <nav class="nav nav-style-1 nav-pills mb-4 nav-justified" role="tablist">
                    @foreach ($dates as $date)
                        @php
                            $formattedDate = date('d', strtotime($date));
                            $dayOfWeek = date('D', strtotime($date));
                        @endphp
                        <a class="nav-link {{ $date == date('Y-m-d') ? 'active' : '' }}" data-bs-toggle="tab"
                            role="tab" href="#{{ strtolower($dayOfWeek) }}">
                            <span class="d-block mb-1">{{ $formattedDate }}</span>
                            <span class="d-block mb-0 op-7 fs-12">{{ $dayOfWeek }}</span>
                        </a>
                    @endforeach
                </nav>
                <div class="tab-content pt-3 my-3">
                    @foreach ($dates as $key => $date)
                        @php
                            $formattedDate = date('d', strtotime($date));
                            $dayOfWeek = date('D', strtotime($date));
                            $isActive = $key === 0 ? 'show active' : ''; // Set the first tab pane as active
                        @endphp
                        <div class="tab-pane {{ $isActive }} border-0 p-0" id="{{ strtolower($dayOfWeek) }}"
                            role="tabpanel">
                            @include('admin.dashboard.todayRepayment', ['date' => $date])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
