<script>
    showMetric('totalLending', 7);
    showMetric('arrearsOverview', 7);
    showMetric('longTermDebtFundAnalysis', 7);
    showMetric('originationOverview', 7);
    showMetric('paymentCollectionsOverview', 7);
    daterange();


    function showMetric(url, duration) {

        var csrfToken = '{{ csrf_token() }}';

        $.ajax({
            url: '{{ route('dashboard.metrics') }}',
            type: 'POST',
            data: {
                url: url,
                duration: duration,
                _token: csrfToken
            },
            success: function(response) {
                $('#' + url).html(response);
            },
            error: function(error) {

            }
        });
    }

    flatpickr("#daterange", {
        mode: "range",
        dateFormat: "Y-m-d",
        onChange: function(selectedDates, dateStr, instance) {
            // Extract "from" and "to" dates
            const fromDate = selectedDates[0];
            const toDate = selectedDates[1];

            // Check if both "from" and "to" dates are available
            if (fromDate && toDate) {
                // Format dates as "YYYY-MM-DD"
                const formattedFromDate = instance.formatDate(fromDate, "Y-m-d");
                const formattedToDate = instance.formatDate(toDate, "Y-m-d");

                // Call the showBadDebt function with the formatted dates
                showBadDebt(formattedFromDate, formattedToDate);
            }
        }
    });

    function showBadDebt(formattedFromDate, formattedToDate) {

        var csrfToken = '{{ csrf_token() }}';


        $.ajax({
            url: '{{ route('dashboard.showBadDebt') }}',
            type: 'POST',
            data: {
                formattedFromDate: formattedFromDate,
                formattedToDate: formattedToDate,
                _token: csrfToken
            },
            success: function(response) {
                $('#showBadDebt').html(response);
            },
            error: function(error) {

            }
        });
    }

    function daterange() {
        const today = new Date();
        const sevenDaysAgo = new Date(today);
        sevenDaysAgo.setDate(today.getDate() - 7);


        const formattedToday = formatDate(today);
        const formattedSevenDaysAgo = formatDate(sevenDaysAgo);

        showBadDebt(formattedToday, formattedSevenDaysAgo);
    }

    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
</script>

<script>
    if (document.querySelector("#courses-earnings") !== null) {
        /* Earnings Report Chart */
        var options = {
            series: [{
                name: "Earnings",
                data: [30, 25, 36, 30, 45, 35, 64, 51, 59, 36, 39, 51]
            }, {
                name: "Students",
                data: [33, 21, 32, 37, 23, 32, 47, 31, 54, 32, 20, 38]
            }],
            chart: {
                height: 340,
                type: "bar",
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: [1.1, 1.1],
                show: true,
                curve: ['smooth', 'smooth'],
            },
            grid: {
                borderColor: '#f3f3f3',
                strokeDashArray: 3
            },
            xaxis: {
                axisBorder: {
                    color: 'rgba(119, 119, 142, 0.05)',
                },
            },
            legend: {
                show: false
            },
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            markers: {
                size: 0
            },
            colors: ["rgb(132, 90, 223)", "#e9e9e9"],
            plotOptions: {
                bar: {
                    columnWidth: "50%",
                    borderRadius: 2,
                }
            },
        };
        document.getElementById('courses-earnings').innerHTML = ''
        var chart1 = new ApexCharts(document.querySelector("#courses-earnings"), options);
        chart1.render();

        function earningsReport() {
            chart1.updateOptions({
                colors: ["rgb(" + myVarVal + ")", "#e9e9e9"],
            })
        }
        /* Earnings Report Chart */
    }
</script>


<script>
    var csrfToken = '{{ csrf_token() }}';

    function generateServiceAbility(id, loanid) {
        $('.servicereport').slideUp();
        $('.process-report').slideDown();

        var progressBar = $('.progress-bar');
        var progressBarLabel = $('.progress-bar-label');
        var progressValue = 0;
        var messages = [
            'Generating Consumer Affordability Report',
            'Analyzing Data',
            'Processing Request'
        ];
        var messageIndex = 0;

        var interval = setInterval(function() {
            if (progressValue < 99) {
                progressValue += 10;
                progressBar.css('width', progressValue + '%');
                progressBarLabel.text(progressValue + '%');
                $('#' + id).find('.card-body .text-change').text(messages[messageIndex]);
                messageIndex = (messageIndex + 1) % messages.length;
            } else {
                clearInterval(interval);
                progressBar.css('width', '99%');
                progressBarLabel.text('99%');
                $('#' + id).find('.card-body .text-change').text('Process completed.');
            }
        }, 5000);

        $.ajax({
            url: '{{ route('bankStatement.consumer.generate') }}',
            type: 'POST',
            data: {
                _token: csrfToken,
                id: id
            },
            success: function(response) {
                $('#' + id).html(response);
                progressBar.css('width', '100%');
                progressBarLabel.text('100%');
                analyzeData(id, loanid);
                clearInterval(interval);
            },
            error: function(error) {
                clearInterval(interval);
                $('.error_msg').show().text(
                    'Error gemerating Consumer Affordability Report! Please try again later.');
            }
        });
    }

    function generateCreditReport(id, loanid) {
        var progressBar = $('.progress-bar');
        var progressBarLabel = $('.progress-bar-label');
        var progressValue = 0;
        var messages = [
            'Generating Credit Report',
            'Analyzing Credit Data',
            'Processing Credit Request'
        ];
        var messageIndex = 0;

        var interval = setInterval(function() {
            if (progressValue < 99) {
                progressValue += 10;
                progressBar.css('width', progressValue + '%');
                progressBarLabel.text(progressValue + '%');
                $('.card-body .text-change').text(messages[messageIndex]);
                messageIndex = (messageIndex + 1) % messages.length;
            } else {
                clearInterval(interval);
                progressBar.css('width', '99%');
                progressBarLabel.text('100%');
                $('.card-body .text-change').text('Credit report generation completed.');

            }
        }, 2000);

        $.ajax({
            url: '{{ route('credit.score.update') }}',
            type: 'POST',
            data: {
                _token: csrfToken,
                id: loanid,
                basiq_user_id: id // Comma was missing here
            },
            success: function(response) {
                $('#' + id).html(response);
                progressBar.css('width', '100%');
                progressBarLabel.text('100%');
                clearInterval(interval);

            },
            error: function(error) {
                clearInterval(interval);
                $('.error_msg').show().text('Error generating credit report! Please try again later.');
                // Handle error, e.g., display an error message to the user
            }
        });
        analyzeData(id, loanid);
    }

    function analyzeData(id, loanid) {
        var progressBar = $('.progress-bar');
        var progressBarLabel = $('.progress-bar-label');
        var progressValue = 0;
        var messages = [
            'Analyzing Data',
            'Processing Request'
        ];
        var messageIndex = 0;

        var interval = setInterval(function() {
            if (progressValue < 99) {
                progressValue += 10;
                progressBar.css('width', progressValue + '%');
                progressBarLabel.text(progressValue + '%');
                $('.card-body .text-change').text(messages[messageIndex]);
                messageIndex = (messageIndex + 1) % messages.length;
            } else {
                clearInterval(interval);
                progressBar.css('width', '99%');
                progressBarLabel.text('100%');
                $('.card-body .text-change').text('Data analysis completed.');
            }
        }, 2000);

        $.ajax({
            url: '{{ route('analytics.detail') }}',
            type: 'POST',
            data: {
                _token: csrfToken,
                id: loanid,
                basiq_user_id: id // Comma was missing here
            },
            success: function(response) {
                $('#' + id).html(response);
                progressBar.css('width', '100%');
                progressBarLabel.text('100%');
                clearInterval(interval);
                location.reload(); // Refresh the page
            },
            error: function(error) {
                clearInterval(interval);
                $('.error_msg').show().text(
                    'Error generating service ability report! Please try again later.');
            }
        });
    }
</script>

<!-- Bad Debt Indicator -->

<script>
    $(document).ready(function() {
        $('#badDebtIndicator input[type="checkbox"]').on('change', function() {

            var isBadDebt = $(this).is(':checked');
            var id = $('input[name="id"]').val(); // Retrieve application ID

            if (isBadDebt) {
                $('.card-header.badDebtIndicator').show();
            } else {
                $('.card-header.badDebtIndicator').hide();
            }

            $('.text-change-span').html('Processing please wait....');

            $.ajax({
                url: '{{ route('update.badDebt') }}',
                type: 'POST',
                data: {
                    _token: csrfToken,
                    isBadDebt: isBadDebt,
                    id: id
                },
                success: function(response) {
                    if (isBadDebt) {
                       $('.text-change-span').html('Marked as bad debt successfully!');
                    } else {
                       $('.text-change-span').html('Un Marked as bad debt successfully!');
                    }
                },
                error: function(error) {
                    $('.text-change-span').html(
                        'Failed to mark as bad debt! Try again later.');
                }
            });

        });
        

        var isInitialBadDebt = $('#badDebtIndicator input[type="checkbox"]').is(':checked');
        if (isInitialBadDebt) {
            $('.card-header.badDebtIndicator').show();
        } else {
            $('.card-header.badDebtIndicator').hide();
        }
    });
</script>
