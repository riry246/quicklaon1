<!-- Start::app-sidebar -->
@inject('setting_helper', 'App\Http\Helpers\SettingHelper')
<aside class="app-sidebar sticky" id="sidebar">

    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header">
        <a href="{{ route('dashboard') }}" class="header-logo">

            <img src="{{ asset('assets/images/logoBlack.png') }}" alt="logo" class="desktop-logo logo-admin">
            <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="desktop-dark logo-admin">

            <img src="{{ asset('assets/images/logoBlack.png') }}" alt="logo" class="toggle-logo">

            <img src="{{ asset('assets/images/brand-logos/toggle-white.png') }}" alt="logo" class="toggle-dark">
            <img src="{{ asset('assets/images/brand-logos/desktop-white.png') }}" alt="logo" class="desktop-white">
            <img src="{{ asset('assets/images/brand-logos/toggle-white.png') }}" alt="logo" class="toggle-white">
        </a>
    </div>
    <!-- End::main-sidebar-header -->

    <!-- Start::main-sidebar -->
    <div class="main-sidebar" id="sidebar-scroll">

        <!-- Start::nav -->
        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                    viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                </svg>
            </div>

            @if ($setting_helper->getAdminGroup() == 'credit-assessor')
                @php
                    $menus = [
                        [
                            'type' => 'category',
                            'label' => 'Main',
                        ],
                        [
                            'type' => 'slide',
                            'label' => 'Dashboard',
                            'route' => 'dashboard',
                            'icon' => 'bx bx-home',
                        ],
                        [
                            'type' => 'category',
                            'label' => 'Loan Application',
                        ],
                        [
                            'type' => 'slide',
                            'label' => 'Applications',
                            'icon' => 'bx bx-copy-alt',
                            'route' => 'javascript:void(0);',
                            'children' => [
                                [
                                    'label' => 'All',
                                    'route' => 'loan.list',
                                    'param' => 'all',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Active',
                                    'route' => 'loan.list',
                                    'param' => 'active',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Pending',
                                    'route' => 'loan.list',
                                    'param' => 'pending',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Processing',
                                    'route' => 'loan.list',
                                    'param' => 'processing',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Declined',
                                    'route' => 'loan.list',
                                    'param' => 'declined',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Completed',
                                    'route' => 'loan.list',
                                    'param' => 'completed',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Incomplete',
                                    'route' => 'loan.list',
                                    'param' => 'incomplete',
                                    'type' => 'link',
                                ],
                            ],
                        ],
                        [
                            'type' => 'slide',
                            'label' => 'Bad Debts',
                            'icon' => 'bx bx-copy-alt',
                            'route' => 'javascript:void(0);',
                            'children' => [
                                [
                                    'label' => 'In Default',
                                    'route' => 'loan.list',
                                    'param' => 'in_default',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Bad Debt',
                                    'route' => 'loan.list',
                                    'param' => 'is_bad_debt',
                                    'type' => 'link',
                                ],
                            ],
                        ],
                        [
                            'type' => 'category',
                            'label' => 'Others',
                        ],
                        [
                            'label' => 'Serviceability Factors',
                            'icon' => 'bx bx-notepad',
                            'type' => 'slide',
                            'children' => [
                                [
                                    'label' => 'Income Factor',
                                    'route' => 'analytics',
                                    'type' => 'link',
                                    'param' => 'income',
                                ],
                                [
                                    'label' => 'Expense  Factor',
                                    'route' => 'analytics',
                                    'type' => 'link',
                                    'param' => 'expenses',
                                ],
                                [
                                    'label' => 'SACC Loans Factor',
                                    'route' => 'analytics',
                                    'type' => 'link',
                                    'param' => 'loans',
                                ],
                            ],
                        ],
                        [
                            'label' => 'Messages',
                            'icon' => 'bx bx-mail-send',
                            'type' => 'slide',
                            'route' => 'message',
                        ],
                        [
                            'label' => 'Follow ups',
                            'icon' => 'bx bx-station',
                            'type' => 'slide',
                            'route' => 'user.index',
                            'children' => [
                                [
                                    'label' => 'All',
                                    'route' => 'case.list',
                                    'param' => 'all',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Open',
                                    'route' => 'case.list',
                                    'param' => 'open',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'In Progress',
                                    'route' => 'case.list',
                                    'param' => 'in_progress',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'On Hold',
                                    'route' => 'case.list',
                                    'param' => 'on_hold',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Resolved',
                                    'route' => 'case.list',
                                    'param' => 'resolved',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Reassigned',
                                    'route' => 'case.list',
                                    'param' => 'reassigned',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Pending Customer',
                                    'route' => 'case.list',
                                    'param' => 'pending_customer',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Pending Review',
                                    'route' => 'case.list',
                                    'param' => 'pending_review',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Closed',
                                    'route' => 'case.list',
                                    'param' => 'closed',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Escalated',
                                    'route' => 'case.list',
                                    'param' => 'escalated',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Rejected',
                                    'route' => 'case.list',
                                    'param' => 'rejected',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Suspended',
                                    'route' => 'case.list',
                                    'param' => 'suspended',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Under Investigation',
                                    'route' => 'case.list',
                                    'param' => 'under_investigation',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Waiting for Approval',
                                    'route' => 'case.list',
                                    'param' => 'waiting_for_approval',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Canceled',
                                    'route' => 'case.list',
                                    'param' => 'canceled',
                                    'type' => 'link',
                                ],
                            ],
                        ],
                    ];
                    $routeParams = request()->route()->parameters();
                    $firstParamValue = reset($routeParams);

                @endphp
            @else
                @php
                    $menus = [
                        [
                            'type' => 'category',
                            'label' => 'Main',
                        ],
                        [
                            'type' => 'slide',
                            'label' => 'Dashboard',
                            'route' => 'dashboard',
                            'icon' => 'bx bx-home',
                        ],
                        [
                            'type' => 'category',
                            'label' => 'Admin Management',
                        ],
                        [
                            'type' => 'slide',
                            'label' => 'User Management',
                            'route' => 'user.index',
                            'icon' => 'bx bx-user',
                            'children' => [
                                [
                                    'label' => 'Admin',
                                    'route' => 'user.index',
                                ],
                                [
                                    'label' => 'Customer',
                                    'route' => 'user.customer',
                                ],
                            ],
                        ],
                        [
                            'type' => 'slide',
                            'label' => 'User Groups',
                            'icon' => 'bx bx-group',
                            'route' => 'javascript:void(0);',
                            'children' => [
                                [
                                    'label' => 'Add New',
                                    'route' => 'group.create',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'List',
                                    'route' => 'group.index',
                                    'type' => 'link',
                                ],
                            ],
                        ],
                        [
                            'type' => 'category',
                            'label' => 'Loan Application',
                        ],
                        [
                            'type' => 'slide',
                            'label' => 'Applications',
                            'icon' => 'bx bx-copy-alt',
                            'route' => 'javascript:void(0);',
                            'children' => [
                                [
                                    'label' => 'All',
                                    'route' => 'loan.list',
                                    'param' => 'all',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Active',
                                    'route' => 'loan.list',
                                    'param' => 'active',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Pending',
                                    'route' => 'loan.list',
                                    'param' => 'pending',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Processing',
                                    'route' => 'loan.list',
                                    'param' => 'processing',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Declined',
                                    'route' => 'loan.list',
                                    'param' => 'declined',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Completed',
                                    'route' => 'loan.list',
                                    'param' => 'completed',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Incomplete',
                                    'route' => 'loan.list',
                                    'param' => 'incomplete',
                                    'type' => 'link',
                                ],
                            ],
                        ],
                        [
                            'type' => 'slide',
                            'label' => 'Bad Debts',
                            'icon' => 'bx bx-copy-alt',
                            'route' => 'javascript:void(0);',
                            'children' => [
                                [
                                    'label' => 'In Default',
                                    'route' => 'loan.list',
                                    'param' => 'in_default',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Bad Debt',
                                    'route' => 'loan.list',
                                    'param' => 'is_bad_debt',
                                    'type' => 'link',
                                ],
                            ],
                        ],
                        [
                            'label' => 'Transaction',
                            'icon' => 'bx bx-money-withdraw',
                            'route' => 'transaction.index',
                            'param' => 'all',
                            'type' => 'slide',
                            'children' => [
                                [
                                    'label' => 'All',
                                    'route' => 'transaction.index',
                                    'param' => 'all',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Principal Deposit',
                                    'route' => 'transaction.principal',
                                    'type' => 'link',
                                ],
                            ],
                        ],
                        [
                            'label' => 'Wallet',
                            'icon' => 'bx bx-wallet',
                            'param' => 'all',
                            'type' => 'slide',
                            'children' => [
                                [
                                    'label' => 'Send',
                                    'route' => 'wallet.send',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Deposit',
                                    'route' => 'wallet.deposit',
                                    'type' => 'link',
                                ],
                            ],
                        ],
                        [
                            'label' => 'Reports',
                            'icon' => 'bx bxs-report',
                            'type' => 'slide',
                            'children' => [
                                [
                                    'label' => 'Reports',
                                    'route' => 'report',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Cohort',
                                    'route' => 'report.cohort',
                                    'type' => 'link',
                                ],
                            ],
                        ],
                        [
                            'type' => 'category',
                            'label' => 'Module Management',
                        ],
                        [
                            'type' => 'slide',
                            'label' => 'Modules',
                            'icon' => 'bx bxs-coin-stack',
                            'route' => 'javascript:void(0);',
                            'children' => [
                                [
                                    'label' => 'Add New',
                                    'route' => 'module.create',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'List',
                                    'route' => 'module.index',
                                    'type' => 'link',
                                ],
                            ],
                        ],
                        [
                            'type' => 'slide',
                            'label' => 'Module Action',
                            'icon' => 'bx bx-list-ol',
                            'route' => 'javascript:void(0);',
                            'children' => [
                                [
                                    'label' => 'Add New',
                                    'route' => 'module_action.create', // Updated route name
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'List',
                                    'route' => 'module_action.index', // Updated route name
                                    'type' => 'link',
                                ],
                            ],
                        ],

                        [
                            'label' => 'Application Form',
                            'type' => 'category',
                        ],
                        [
                            'label' => 'Form Settings',
                            'icon' => 'bx bx-notepad',
                            'type' => 'slide',
                            'children' => [
                                [
                                    'label' => 'Reason For Loan',
                                    'route' => 'reason.index',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Questions',
                                    'route' => 'question.index',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Terms & Conditions',
                                    'route' => 'term.index',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Bank',
                                    'route' => 'bank.index',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Document Type',
                                    'route' => 'document_type.index',
                                    'type' => 'link',
                                ],
                            ],
                        ],
                        [
                            'label' => 'Templates',
                            'icon' => 'bx bx-notepad',
                            'type' => 'slide',
                            'children' => [
                                [
                                    'label' => 'Email Templates',
                                    'route' => 'email.template.index',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'SMS Templates',
                                    'route' => 'sms.template.index',
                                    'type' => 'link',
                                ],
                            ],
                        ],
                        [
                            'label' => 'Serviceability Factors',
                            'icon' => 'bx bx-notepad',
                            'type' => 'slide',
                            'children' => [
                                [
                                    'label' => 'Income Factor',
                                    'route' => 'analytics',
                                    'type' => 'link',
                                    'param' => 'income',
                                ],
                                [
                                    'label' => 'Expense  Factor',
                                    'route' => 'analytics',
                                    'type' => 'link',
                                    'param' => 'expenses',
                                ],
                                [
                                    'label' => 'SACC Loans Factor',
                                    'route' => 'analytics',
                                    'type' => 'link',
                                    'param' => 'loans',
                                ],
                            ],
                        ],
                        [
                            'label' => 'SMS',
                            'icon' => 'bx bx-envelope',
                            'type' => 'slide',
                            'route' => 'sms',
                        ],
                        [
                            'label' => 'Messages',
                            'icon' => 'bx bx-mail-send',
                            'type' => 'slide',
                            'route' => 'message',
                        ],
                        [
                            'label' => 'Follow ups',
                            'icon' => 'bx bx-station',
                            'type' => 'slide',
                            'route' => 'user.index',
                            'children' => [
                                [
                                    'label' => 'All',
                                    'route' => 'case.list',
                                    'param' => 'all',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Open',
                                    'route' => 'case.list',
                                    'param' => 'open',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'In Progress',
                                    'route' => 'case.list',
                                    'param' => 'in_progress',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'On Hold',
                                    'route' => 'case.list',
                                    'param' => 'on_hold',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Resolved',
                                    'route' => 'case.list',
                                    'param' => 'resolved',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Reassigned',
                                    'route' => 'case.list',
                                    'param' => 'reassigned',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Pending Customer',
                                    'route' => 'case.list',
                                    'param' => 'pending_customer',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Pending Review',
                                    'route' => 'case.list',
                                    'param' => 'pending_review',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Closed',
                                    'route' => 'case.list',
                                    'param' => 'closed',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Escalated',
                                    'route' => 'case.list',
                                    'param' => 'escalated',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Rejected',
                                    'route' => 'case.list',
                                    'param' => 'rejected',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Suspended',
                                    'route' => 'case.list',
                                    'param' => 'suspended',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Under Investigation',
                                    'route' => 'case.list',
                                    'param' => 'under_investigation',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Waiting for Approval',
                                    'route' => 'case.list',
                                    'param' => 'waiting_for_approval',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Canceled',
                                    'route' => 'case.list',
                                    'param' => 'canceled',
                                    'type' => 'link',
                                ],
                            ],
                        ],
                        [
                            'label' => 'Wheel of fortune',
                            'icon' => 'bx bx-color side-menu__icon',
                            'type' => 'slide',
                            'children' => [
                                [
                                    'label' => 'Wheel of Fortune',
                                    'route' => 'wheel',
                                    'type' => 'link',
                                ],
                            ],
                        ],
                        [
                            'label' => 'Leads',
                            'icon' => 'bx bx-bar-chart side-menu__icon',
                            'type' => 'slide',
                            'children' => [
                                [
                                    'label' => 'Sold Leads',
                                    'route' => 'leadmarket.index',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Bought Leads',
                                    'route' => 'leadmarket.bought.index',
                                    'type' => 'link',
                                ],
                            ],
                        ],
                        [
                            'label' => 'Site Configs',
                            'type' => 'category',
                            'name' => 'Site Configs',
                        ],
                        [
                            'label' => 'Settings',
                            'icon' => 'bx bx-cog side-menu__icon',
                            'type' => 'slide',
                            'children' => [
                                [
                                    'label' => 'General',
                                    'route' => 'settings',
                                    'param' => 'general-information',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Activity Score Settings',
                                    'route' => 'activity.score.index',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Import User',
                                    'route' => 'user.import',
                                    'type' => 'link',
                                ],
                            ],
                        ],
                        [
                            'label' => 'Automations',
                            'icon' => 'bx bx-cog side-menu__icon',
                            'type' => 'slide',
                            'children' => [
                                [
                                    'label' => 'Check Bad Debts',
                                    'route' => 'check.badDebt',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Check Overdue Interest',
                                    'route' => 'check.interest',
                                    'type' => 'link',
                                ],
                                [
                                    'label' => 'Correct Interest',
                                    'route' => 'correct.interest',
                                    'type' => 'link',
                                ],
                            ],
                        ],
                    ];
                    $routeParams = request()->route()->parameters();
                    $firstParamValue = reset($routeParams);

                @endphp
            @endif
            <ul class="main-menu">
                @foreach ($menus as $menu)
                    @if ($menu['type'] == 'category')
                        <li class="slide__category"><span class="category-name">{{ $menu['label'] }}</span></li>
                    @elseif ($menu['type'] == 'slide')
                        @if (isset($menu['children']))
                            <li class="slide has-sub">
                                <a href="javascript:void(0);" class="side-menu__item parent_li">
                                    <i class="{{ $menu['icon'] }} side-menu__icon"></i>
                                    <span class="side-menu__label">{{ $menu['label'] }}</span>
                                    <i class="fe fe-chevron-right side-menu__angle"></i>
                                </a>

                                <ul class="slide-menu child1">
                                    @foreach ($menu['children'] as $sub)
                                        <li class="slide side-menu__label1">
                                            <a href="javascript:void(0)">{{ $menu['label'] }}</a>
                                        </li>
                                        <li class="slide">
                                            @if (isset($sub['param']))
                                                <a href="{{ route($sub['route'], isset($sub['param']) ? [$sub['param']] : []) }}"
                                                    class="side-menu__item {{ Request::route()->getName() == $sub['route'] && $firstParamValue == $sub['param'] ? 'active' : '' }}">{{ $sub['label'] }}</a>
                                            @else
                                                <a href="{{ route($sub['route'], isset($sub['param']) ? [$sub['param']] : []) }}"
                                                    class="side-menu__item {{ Request::route()->getName() == $sub['route'] ? 'active' : '' }}">{{ $sub['label'] }}</a>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li class="slide">
                                <a href="{{ route($menu['route']) }}"
                                    class="parent_li side-menu__item {{ Request::route()->getName() == $menu['route'] ? 'active' : '' }}">
                                    <i class="{{ $menu['icon'] }} side-menu__icon "></i>
                                    <span class="side-menu__label">{{ $menu['label'] }}</span>
                                </a>
                            </li>
                        @endif
                    @endif
                @endforeach
            </ul>

            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                    width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                </svg></div>
        </nav>
        <!-- End::nav -->

    </div>
    <!-- End::main-sidebar -->

</aside>
<!-- End::app-sidebar -->
