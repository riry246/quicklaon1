<div class="d-block justify-content-between my-4 page-header-breadcrumb">
    <h1 class="page-title fw-semibold fs-4 mb-0">{{ $breadcrumb['module_name'] }}
        @if (isset($time))
            <p class="fs-13 fw-normal mt-1" id="current-time">{{ now()->format('l, j F Y, h:i:s A') }}</p>
            <script>
                // Function to update the time in Sydney, Australia
                function updateAustraliaTime() {
                    var australiaTimeElement = document.getElementById("current-time");

                    // Get the current date and time in Sydney time zone
                    var sydneyTime = "Melbourne: "+new Date().toLocaleString('en-AU', {
                        timeZone: 'Australia/Sydney',
                        weekday: 'long', // Full name of the day of the week (e.g., Monday)
                        day: 'numeric', // Day of the month without leading zeros (e.g., 5)
                        month: 'long', // Full name of the month (e.g., January)
                        year: 'numeric', // Full year (e.g., 2024)
                        hour: 'numeric', // Hour in 12-hour format (e.g., 4)
                        minute: 'numeric', // Minutes (e.g., 30)
                        second: 'numeric', // Seconds (e.g., 25)
                        hour12: true // Use 12-hour format (AM/PM)
                    });

                    australiaTimeElement.innerHTML = sydneyTime;
                }

                // Update the time every second (1000 milliseconds)
                setInterval(updateAustraliaTime, 1000);
            </script>
        @endif
    </h1>

    @if (isset($time))
    @else
        <div class="ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"> <a
                            href="{{ route($breadcrumb['url'], $breadcrumb['id'] ?? null) }}">{{ $breadcrumb['module_name'] }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb['function'] }}</li>
                </ol>
            </nav>
        </div>
    @endif

</div>
@include('admin/widgets/messages')
