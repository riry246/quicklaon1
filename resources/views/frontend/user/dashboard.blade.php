@extends('layouts.customer')

@section('content')
<customer-dashboard-component :json-data="{{ json_encode($data) }}"></customer-dashboard-component>
    
@endsection
