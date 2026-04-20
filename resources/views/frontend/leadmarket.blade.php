@extends('layouts.leadmarket')

@section('content')
    <?php
        // Get the value of LM_SELLER_CLIENT_ID from the environment
        $lmSellerClientId = env('LM_SELLER_CLIENT_ID');
    ?>
    
    <script src="https://static.creditsense.com.au/iframe/mlm-loader.js?aff={{ $lmSellerClientId }}" data-defer="true" id="mlm-loader-1"></script>

    <script>
        var event = new CustomEvent('mlm.init', {
            detail: {
                tok: '{{ $token }}',
            }
        });
        document.getElementById('mlm-loader-1').dispatchEvent(event);
    </script>
@endsection
