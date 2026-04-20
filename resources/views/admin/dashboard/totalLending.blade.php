<div class="col-lg-12 col-sm-12 col-md-12 col-xxl-12 col-xl-12">
    <div class="card custom-card">
        <div class="card-header justify-content-between flex-wrap">
            <div class="card-title">
                 Total Lending
            </div>
            @include('admin.dashboard.widgets.days', [
                'period' => $totalLending['period'],
                'url' => $totalLending['url'],
            ])
        </div>
        <div class="card-body p-0">
            @include('admin.dashboard.widgets.metricHeader', ['metrics' => $totalLending])
            <div id="totallending" class="p-3"></div>
        </div>
    </div>
</div>

<script>
    // for NFTs Statistics
    var options = {
        series: [
        @foreach ($totalLending as $k => $v)
        {
            name: "{{$k}}",
            data: [20, 38, 38, 72, 55, 63, 43]
        }, 
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
        colors: ["rgb(132, 90, 223)", "rgba(132, 90, 223, 0.3)"],
        yaxis: {
            title: {
                text: 'Amount',
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
            type: 'month',
            categories: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thrusday', 'Friday', 'Saturday'],
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
    document.getElementById('totallending').innerHTML = ''
    var chart = new ApexCharts(document.querySelector("#totallending"), options);
    chart.render();

    function nftStatistics() {
        chart.updateOptions({
            colors: ["rgb(" + myVarVal + ")", "rgba(" + myVarVal + ", 0.3)"],
        })
    }
</script>