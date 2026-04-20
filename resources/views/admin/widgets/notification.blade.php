<div class="header-element notifications-dropdown">
    <!-- Start::header-link|dropdown-toggle -->
    <a href="javascript:void(0);" class="header-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside"
        id="messageDropdown" aria-expanded="false">
        <i class="bx bx-bell header-link-icon"></i>
        @if (count(auth()->user()->unreadNotifications) > 0)
            <span class="badge bg-secondary rounded-pill header-icon-badge pulse pulse-secondary"
                id="notification-icon-badge">{{ count(auth()->user()->unreadNotifications) }}</span>
        @endif
    </a>
    <!-- End::header-link|dropdown-toggle -->
    <!-- Start::main-header-dropdown -->
    <div class="main-header-dropdown dropdown-menu dropdown-menu-end" data-popper-placement="none">
        <div class="p-3">
            <div class="d-flex align-items-center justify-content-between">
                <p class="mb-0 fs-17 fw-semibold">Notifications</p>
                @if (count(auth()->user()->unreadNotifications) > 0)
                    <a href="{{ route('mark-as-read') }}"><span class="badge bg-secondary-transparent"
                            id="notifiation-data">Mark all as read</span></a>
                @endif
            </div>
        </div>
        <div class="dropdown-divider"></div>
        @if (count(auth()->user()->unreadNotifications) > 0)
            <ul class="list-unstyled mb-0" id="header-notification-scroll" style="overflow:scroll">
                @foreach (auth()->user()->unreadNotifications as $notification)
                    @php
                        $notificationData = json_decode($notification->data['data'], true);
                    @endphp
                    <li class="dropdown-item">
                        <div class="d-flex align-items-start">
                            <div class="pe-2">
                                <span
                                    class="avatar avatar-md bg-{{ $notificationData['color'] }}-transparent avatar-rounded"><i
                                        class="ti {{ $notificationData['icon'] }} fs-18"></i></span>
                            </div>
                            <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="mb-0 fw-semibold"><a
                                            href="{{ $notificationData['url'] ?? '#' }}">{{ $notificationData['heading'] }}</a>
                                    </p>
                                    <span
                                        class="text-muted fw-normal fs-12 header-notification-text">{{ $notificationData['msg'] }}</span>
                                </div>

                            </div>
                        </div>
                    </li>
                @endforeach

            </ul>
            <div class="p-3 empty-header-item1 border-top">
                <div class="d-grid">
                    <a href="{{ route('notiifcaiton') }}" class="btn btn-primary">View All</a>
                </div>
            </div>
        @else
            <div class="p-5 empty-item1 ">
                <div class="text-center">
                    <span class="avatar avatar-xl avatar-rounded bg-secondary-transparent">
                        <i class="ri-notification-off-line fs-2"></i>
                    </span>
                    <h6 class="fw-semibold mt-3">No New Notifications</h6>
                </div>
            </div>
        @endif
    </div>
    <!-- End::main-header-dropdown -->
</div>
