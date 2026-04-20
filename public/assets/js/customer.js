<script type="text/javascript">
    document.tidioChatCode = "dtqf4lmhwlkxrd3fmxeiz38kddyhesay";
    (function() {
        function asyncLoad() {
            var tidioScript = document.createElement("script");
            tidioScript.type = "text/javascript";
            tidioScript.async = true;
            tidioScript.src = "//code.tidio.co/dtqf4lmhwlkxrd3fmxeiz38kddyhesay.js";
            document.body.appendChild(tidioScript);
        }
        if (window.attachEvent) {
        window.attachEvent("onload", asyncLoad);
        } else {
        window.addEventListener("load", asyncLoad, false);
        }
    })();

    $(document).ready(function() {
        // Add a click event listener to the button
        $('.chat-button').click(function () {
            window.tidioChatApi.show();
            window.tidioChatApi.open();
        });
    });
</script>
