<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="dark" data-header-styles="dark"
    data-menu-styles="dark" data-toggled="close">

<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=no'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        {{ isset($breadcrumb['function']) ? $breadcrumb['function'] : 'Quick Cash Loans from $300 - $2000 - CashFaster ' }}
    </title>
    <meta name="Description" content="Cashfaster">
    <meta name="Author" content="Frenzyard">
    <meta name="keywords" content="">

    <!-- Favicon -->

    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ asset('/assets/images/brand-logos/fav/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ asset('/assets/images/brand-logos/fav/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ asset('/assets/images/brand-logos/fav/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('/assets/images/brand-logos/fav/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('/assets/images/brand-logos/fav/safari-pinned-tab.svg') }}" color="#003788">
    <meta name="msapplication-TileColor" content="#003788">
    <meta name="theme-color" content="#003788">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Choices JS -->
    <script src="{{ asset('assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
    <script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
    <!-- Main Theme Js -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    @include('includes.stylesheet')

</head>
@inject('loan_helper', 'App\Http\Helpers\LoanHelper')

<body id="admin">
    @include('admin/widgets/switcher')
    @include('admin/widgets/loader')
    @include('admin/widgets/header')
    @include('admin/widgets/sideNav')

    <div class="page">
        <div class="main-content app-content">
            @yield('content')
        </div>
    </div>
    <!-- Scroll To Top -->
    <div class="scrollToTop">
        <span class="arrow"><i class="ri-arrow-up-s-fill fs-20"></i></span>
    </div>
    <div id="responsive-overlay"></div>
    @include('admin/widgets/search')

    @include('includes.javascript')
    @include('includes.custom')
    @include('includes.customjavascript')
    <script>
        $(document).ready(function() {
            // Check if any child has the "active" class and add "active" to the parent
            $('.slide-menu .side-menu__item.active').parent().parent().parent().find('.parent_li').addClass(
                'active');
            $('.slide-menu .side-menu__item.active').parent().parent().parent().addClass('active open');
            $('.slide-menu .side-menu__item.active').parent().parent().addClass('active open d-block');
        });
    </script>
    <script>
        this.message(2);

        $("#sendCode").click(function() {
            var button = $(this); // Reference to the button clicked
            var timer; // Variable to hold the timer
            button.text('Sending....').prop('disabled', true);

            $.ajax({
                url: '{{ route('loan.authcode') }}',
                type: 'GET',
                success: function(response) {
                    $('.authcodesent').show();
                    console.log('Authentication code sent successfully:', response);

                    // Change button text and disable it for 1 minute
                    button.text('Code Sent').prop('disabled', true);

                    var countdown = 60; // Countdown duration in seconds
                    timer = setInterval(function() {
                        countdown--;
                        button.text('Resend Code (' + countdown + 's)');
                        if (countdown <= 0) {
                            clearInterval(timer);
                            button.text('Generate Transaction Code').prop('disabled', false);
                        }
                    }, 1000); // Update every second
                },
                error: function(error) {
                    button.text('Generate Transaction Code').prop('disabled', false);
                    alert('Error sending authentication code:', error);
                }
            });

            // Clear the timer when the button is clicked again
            button.on('click', function() {
                clearInterval(timer);
            });
        });

        $("#sendTransacationCode").click(function() {
            var button = $(this); // Reference to the button clicked
            var timer; // Variable to hold the timer
            button.text('Sending....').prop('disabled', true);

            $.ajax({
                url: '{{ route('loan.authcode.generator') }}',
                type: 'GET',
                success: function(response) {
                    $('.authcodesent').show();
                    console.log('Authentication code sent successfully:', response);

                    // Change button text and disable it for 1 minute
                    button.text('Code Sent').prop('disabled', true);

                    var countdown = 60; // Countdown duration in seconds
                    timer = setInterval(function() {
                        countdown--;
                        button.text('Resend Code (' + countdown + 's)');
                        if (countdown <= 0) {
                            clearInterval(timer);
                            button.text('Generate Transaction Code').prop('disabled', false);
                        }
                    }, 1000); // Update every second
                },
                error: function(error) {
                    button.text('Generate Transaction Code').prop('disabled', false);
                    alert('Error sending authentication code:', error);
                }
            });

            // Clear the timer when the button is clicked again
            button.on('click', function() {
                clearInterval(timer);
            });
        });

        function toggleTable(id) {
            $('.transactions_details').slideUp();
            $('#' + id).slideDown('slow');
        }

        function message(id) {

            $.ajax({
                url: '{{ route('message.view', 2) }}',
                type: 'GET',
                data: {
                    id: id
                }, // Include the id parameter in the request
                success: function(response) {
                    $('.main-chat-area').html(response);

                    var chatContent = $('.chat-content');
                    chatContent.animate({
                        scrollTop: chatContent[0].scrollHeight
                    }, 'slow');
                },
                error: function(error) {
                    console.error('Error sending authentication code:', error);
                }
            });
        }

        function submit(id) {

            var subject = $('#subject').val();
            var content = $('#msgcontent').val();
            var type = $('#type').val();
            var csrfToken = '{{ csrf_token() }}';


            $.ajax({
                url: '{{ route('message.send') }}',
                type: 'POST',
                data: {
                    subject: subject,
                    content: content,
                    type: type,
                    user_id: id,
                    _token: csrfToken
                },
                success: function(response) {
                    message(id);
                },
                error: function(error) {
                    // Handle the error response
                    console.error('Error:', error);
                }
            });
        }

        function sms(id) {
            $.ajax({
                url: '{{ url('admin/sms/view') }}/' + id,
                type: 'GET',
                success: function(response) {
                    $('.main-chat-area-sms').html(response);

                    var chatContent = $('.chat-content');
                    chatContent.animate({
                        scrollTop: chatContent[0].scrollHeight
                    }, 'slow');
                },
                error: function(error) {
                    console.error('Error fetching SMS:', error);
                }
            });
        }
    </script>
</body>

</html>
