@extends('layouts.customer')

@section('content')


    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between  page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Notification</h1>

    </div>
    <!-- Page Header Close -->

    <div class="container">
        @if (count(auth()->user()->unreadNotifications) > 0)
            <ul class="timeline list-unstyled">
                @foreach (auth()->user()->unreadNotifications as $notification)
                    @php
                        $notificationData = json_decode($notification->data['data'], true);
                    @endphp
                    <li>
                        <div class="timeline-time text-end">
                            <span class="date">{{ date('l', strtotime($notification->created_at)) }}</span>
                            <span class="time d-inline-block">{{ date('H:i', strtotime($notification->created_at)) }}</span>
                        </div>
                        <div class="timeline-icon">
                            <a href="javascript:void(0);"></a>
                        </div>
                        <div class="timeline-body">
                            <div class="d-flex align-items-top timeline-main-content flex-wrap mt-0">
                                <div class="avatar avatar-md online me-3 avatar-rounded mt-sm-0 mt-4">
                                    <span
                                        class="avatar avatar-md bg-{{ $notificationData['color'] }}-transparent avatar-rounded"><i
                                            class="ti {{ $notificationData['icon'] }} fs-18"></i></span>
                                </div>
                                <div class="flex-fill">
                                    <div class="d-flex align-items-center">
                                        <div class="mt-sm-0 mt-2">
                                            <p class="mb-0 fs-14 fw-semibold"><a
                                                    href="{{ $notificationData['url'] ?? '#' }}">{{ $notificationData['heading'] }}</a>
                                            </p>
                                            <p class="mb-0 text-muted">{{ $notificationData['msg'] }}</p>
                                        </div>
                                        <div class="ms-auto">
                                            <span class="float-end badge bg-light text-muted timeline-badge">
                                                {{ date('d M, Y', strtotime($notification->created_at)) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="p-5 m-5 empty-item1 ">
                <div class="text-center">
                    <span class="avatar avatar-xl avatar-rounded bg-secondary-transparent">
                        <i class="ri-notification-off-line fs-2"></i>
                    </span>
                    <h6 class="fw-semibold mt-3">No New Notifications</h6>
                </div>
            </div>
        @endif
    </div>


@endsection
