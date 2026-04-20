<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

<!-- Custom-Switcher JS
<script src="{{ asset('assets/js/custom-switcher.min.js') }}"></script> -->

<!-- Bootstrap JS -->
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Swiper JS -->
<script src="{{ asset('assets/libs/swiper/swiper-bundle.min.js') }}"></script>

<!-- Internal Sing-Up JS -->
<script src="{{ asset('assets/js/authentication.js') }}"></script>

<!-- Show Password JS -->
<script src="{{ asset('assets/js/show-password.js') }}"></script>


<!-- Popper JS -->
<script src="{{ asset('assets/libs/@popperjs/core/umd/popper.min.js') }}"></script>

<!-- Defaultmenu JS -->
<script src="{{ asset('assets/js/defaultmenu.min.js') }}"></script>

<!-- Node Waves JS-->
<script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>

<!-- Sticky JS -->
<script src="{{ asset('assets/js/sticky.js') }}"></script>

<!-- Simplebar JS -->
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/simplebar.js') }}"></script>

<!-- Color Picker JS -->
<script src="{{ asset('assets/libs/@simonwep/pickr/pickr.es5.min.js') }}"></script>

<!-- Apex Charts JS -->
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>

<!-- JSVector Maps JS -->
<script src="{{ asset('assets/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>

<!-- JSVector Maps MapsJS -->
<script src="{{ asset('assets/libs/jsvectormap/maps/world-merc.js') }}"></script>

<!-- Date & Time Picker JS -->
<script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>

<!-- Quill Editor JS
<script src="{{ asset('assets/libs/quill/quill.min.js') }}"></script>-->

<!-- Internal Quill JS
<script src="{{ asset('assets/js/quill-editor.js') }}"></script> -->



<!-- Sales-Dashboard JS
<script src="{{ asset('assets/js/sales-dashboard.js') }}"></script>-->

<script src="{{ asset('assets/js/personal-dashboard.js') }}"></script>
<script src="{{ asset('assets/js/chat.js') }}"></script>


<script src="{{ asset('assets/libs/chart.js/chart.min.js') }}"></script>
<script src="{{ asset('assets/js/chartjs-charts.js') }}"></script>




<script>
    CKEDITOR.replace('content');
</script>


<!-- Datatables Cdn -->
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.6/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- Internal Datatables JS -->
<script src="{{ asset('assets/js/datatables.js') }}"></script>
<script src="{{ asset('assets/js/choices.js') }}"></script>

<!-- noUiSlider JS
<script src="{{ asset('assets/libs/nouislider/nouislider.min.js') }}"></script>
<script src="{{ asset('assets/libs/wnumb/wNumb.min.js') }}"></script>
<script src="{{ asset('assets/js/nouislider.js') }}"></script>
-->
<script src="{{ asset('assets/js/custom.js') }}"></script>
<script src="{{ asset('assets/libs/chart.js/chart.min.js') }}"></script>

<!-- Date & Time Picker JS -->
<script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/js/date&time_pickers.js') }}"></script>


<script src="{{ asset('assets/libs/particles.js/particles.js') }}"></script>
<script src="{{ asset('assets/js/error.js') }}"></script>

<script>
    let wheel = document.querySelector('.wheel');
    let spinBtn = document.querySelector('.spinBtn');
    let spinning = false;
    let value = 0;

    spinBtn.onclick = function() {
        if (!spinning) {
            spinning = true;
            let randomRotation = Math.ceil(Math.random() * 3600);
            let totalRotation = randomRotation + 360 * 5;
            wheel.style.transition = "transform 5s ease-in-out";
            wheel.style.transform = `rotate(${totalRotation}deg)`;
            value = randomRotation;
        }
    }

    wheel.addEventListener("transitionend", function() {
        let style = window.getComputedStyle(wheel);
        let matrix = style.transform || style.webkitTransform;
        let values = matrix.match(/matrix\((.*)\)/)[1].split(", ");
        let rotation = Math.round(Math.atan2(values[0], values[1]) * (180 / Math.PI))
        console.log(rotation);
        let finalValue = (rotation + 360) % 360;

        console.log("The wheel stopped at: " + finalValue + " degrees");

        const sectionRanges = [{
                min: 0,
                max: 45,
                prize: "100"
            },
            {
                min: 45,
                max: 90,
                prize: "200"
            },
            {
                min: 90,
                max: 135,
                prize: "300"
            },
            {
                min: 135,
                max: 180,
                prize: "400"
            },
            {
                min: 180,
                max: 225,
                prize: "500"
            },
            {
                min: 225,
                max: 270,
                prize: "600"
            },
            {
                min: 270,
                max: 315,
                prize: "700"
            },
            {
                min: 315,
                max: 360,
                prize: "800"
            },
        ];

        let prize = "No Prize";

        for (const section of sectionRanges) {
            if (finalValue >= section.min && finalValue < section.max) {
                prize = section.prize;
                break;
            }
        }

        console.log("Prize: " + prize);
        updateNumberBlockColor(prize);
        spinning = false;
    });

    function updateNumberBlockColor(prize) {
        const numberBlocks = document.querySelectorAll('.wheel .number');
        numberBlocks.forEach(function(block) {
            const blockPrize = block.getAttribute('data-prize');
            if (blockPrize === prize) {
                block.classList.add('blink');
                document.getElementById('amountValue').textContent = '$' + prize; // Update the selected amount
                var tempUser = document.getElementById('temp_user');
                var textContent = tempUser.textContent;
                document.getElementById('user').textContent = textContent;
            } else {
                block.style.background = block.style.getPropertyValue('--clr');
                block.classList.remove('blink');
            }
        });
    }
</script>
