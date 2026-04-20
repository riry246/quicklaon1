@inject('dashboard_helper', 'App\Http\Helpers\DashboardHelper')

<div class="card custom-card">
    <div class="card-header justify-content-between flex-wrap">
        <div class="card-title">
            <i class="bi bi-graph-up-arrow text-secondary"></i> &nbsp {{ $metrics['title'] }}
        </div>
        @include('admin.dashboard.widgets.days', [
            'period' => $metrics['metrics']['period'],
            'url' => $metrics['url'],
        ])
    </div>
    <div class="card-body p-0">
        @include('admin.dashboard.widgets.metricHeader', ['metricshead' => $metrics['metrics']])
        <div id="{{ $metrics['url'] }}stat" class="p-3"></div>
    </div>
</div>


<script>
    // for NFTs Statistics
    var options = {
        series: [
            @foreach ($metrics['metrics'] as $k => $v)
                @if ($k != 'period')
                    {
                        name: "{{ $dashboard_helper->removeSlug(ucfirst($k)) }}",
                        data: [
                            @foreach ($v['dateWise'] as $d)
                                {{ $dashboard_helper->convertFormattedNumberToNumeric($d) }},
                            @endforeach
                        ]
                    },
                @endif
            @endforeach
        ],
        chart: {
            height: 343,
            type: 'line',
            zoom: {
                enabled: false
            },
            dropShadow: {
                enabled: true,
                enabledOnSeries: undefined,
                top: 5,
                left: 0,
                blur: 3,
                color: '#000',
                opacity: 0.1
            },
        },
        dataLabels: {
            enabled: false
        },
        legend: {
            position: "top",
            horizontalAlign: "center",
            offsetX: -15,
            fontWeight: "bold",
        },
        stroke: {
            curve: 'smooth',
            width: '3',
            dashArray: [0, 5],
        },
        grid: {
            borderColor: '#f2f6f7',
        },
        colors: ["rgb(132, 90, 223)", "rgba(132, 90, 223, 0.3)", "rgb(163, 143, 180)", "rgb(67, 134, 17)", ],
        yaxis: {
            title: {
                text: 'Points',
                style: {
                    color: '#adb5be',
                    fontSize: '14px',
                    fontFamily: 'poppins, sans-serif',
                    fontWeight: 600,
                    cssClass: 'apexcharts-yaxis-label',
                },
            },
            labels: {
                formatter: function(y) {
                    return y.toFixed(0) + "";
                }
            }
        },
        xaxis: {
            type: 'days',
            title: {
                text: 'Dates',
                style: {
                    color: '#adb5be',
                    fontSize: '14px',
                    fontFamily: 'poppins, sans-serif',
                    fontWeight: 600,
                    cssClass: 'apexcharts-yaxis-label',
                },
            },
            labels: {
                formatter: function(y) {
                    return y.toFixed(0) + "";
                }
            },
            categories: [
                @foreach ($metrics['timesheet'] as $d)
                    '{{ $d }}',
                @endforeach
            ],

            axisBorder: {
                show: true,
                color: 'rgba(119, 119, 142, 0.05)',
                offsetX: 0,
                offsetY: 0,
            },
            axisTicks: {
                show: true,
                borderType: 'solid',
                color: 'rgba(119, 119, 142, 0.05)',
                width: 6,
                offsetX: 0,
                offsetY: 0
            },
            labels: {
                rotate: -90
            }
        }
    };
    var divid = '{{ $metrics['url'] }}stat';
    document.getElementById(divid).innerHTML = ''
    var chart = new ApexCharts(document.querySelector("#" + divid), options);
    chart.render();

    function nftStatistics() {
        chart.updateOptions({
            colors: ["rgb(" + myVarVal + ")", "rgba(" + myVarVal + ", 0.3)"],
        })
    }
</script>
