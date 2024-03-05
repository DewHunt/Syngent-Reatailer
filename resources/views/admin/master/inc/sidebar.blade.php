@php
    // dd(isThisMenuActive('bpromoter.index'));
@endphp
<div class="sidebar">
    <div class="sidebar-inner">
        <div class="sidebar-logo">            
            {{-- <div class="peers ai-c fxw-nw">
                <div class="peer peer-greed">
                    <a class="sidebar-link td-n" href="{{ route('home') }}" class="td-n">
                        <div class="peers ai-c fxw-nw">
                            
                            <div class="peer">
                                <div class="logo">
                                    <img src="{{asset('public/admin/static/images/syngenta_logo.png')}}" alt="WRG" width="70" height="50"/>
                                </div>
                            </div>

                            <div class="peer peer-greed">
                                <h5 class="lh-1 mB-0 logo-text" style="margin-top: 30px;">Retail Gear</h5>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="peer">
                    <div class="mobile-toggle sidebar-toggle">
                        <a href="#" class="td-n"><i class="ti-arrow-circle-left"></i></a>
                    </div>
                </div>
            </div> --}}            
        </div>

        <ul class="sidebar-menu scrollable pos-r">
            @if (isThisMenuActive('home'))
                <li class="nav-item mT-5 active">
                    <a class="sidebar-link" href="{{route('home')}}" default>
                        <span class="icon-holder"><i class="c-light-blue-500 fa fa-home"></i></span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>
            @endif

            @if (isThisMenuActive('dealer.index'))
                <li class="nav-item">
                    <a class="sidebar-link" href="{{route('dealer.index')}}">
                        <span class="icon-holder"><i class="c-light-blue-500 fa fa-group"></i> </span>
                        <span class="title">Dealer</span>
                    </a>
                </li>
            @endif

            @if (isThisMenuActive('distribution.index'))
                <li class="nav-item" style="display: none">
                    <a class="sidebar-link" href="{{route('distribution.index')}}">
                        <span class="icon-holder"><i class="c-light-blue-500 fa fa-group"></i> </span>
                        <span class="title">Dealer Distribution</span>
                    </a>
                </li>
            @endif

            {{-- @if (isThisMenuActive('rsm.index'))
                <li class="nav-item">
                    <a class="sidebar-link" href="{{route('rsm.index')}}">
                        <span class="icon-holder"><i class="c-light-blue-500 fa fa-user"></i> </span>
                        <span class="title">Rsm</span>
                    </a>
                </li>
            @endif --}}

            @if (isThisMenuActive('category.index'))
                <li class="nav-item">
                    <a class="sidebar-link" href="{{route('category.index')}}">
                        <span class="icon-holder"><i class="c-light-blue-500 fa fa-tachometer"></i> </span>
                        <span class="title">Category</span>
                    </a>
                </li>
            @endif

            @if (isThisMenuActive('product.index'))
                <li class="nav-item">
                    <a class="sidebar-link" href="{{route('product.index')}}">
                        <span class="icon-holder"><i class="c-light-blue-500 fa fa-tachometer"></i> </span>
                        <span class="title">Brand</span>
                    </a>
                </li>
            @endif

            @if (isThisMenuActive('employee.index'))
                <li class="nav-item">
                    <a class="sidebar-link" href="{{route('employee.index')}}">
                        <span class="icon-holder"><i class="c-light-blue-500 fa fa-group"></i> </span>
                        <span class="title">Employee</span>
                    </a>
                </li>
            @endif

            @if (isThisMenuActive('zone.index'))
                <li class="nav-item">
                    <a class="sidebar-link" href="{{route('zone.index')}}">
                        <span class="icon-holder"><i class="c-light-blue-500 fa fa-server"></i> </span>
                        <span class="title">Zone</span>
                    </a>
                </li>
            @endif

            @if (isThisMenuActive('GroupCategoryController'))
                <li class="nav-item">
                    <a class="sidebar-link" href="{{ route('GroupCategoryController') }}">
                        <span class="icon-holder"><i class="c-light-blue-500 fa fa-server"></i> </span>
                        <span class="title">Group Category</span>
                    </a>
                </li>
            @endif

            @if (isThisMenuActive('retailer.index'))
                <li class="nav-item">
                    <a class="sidebar-link" href="{{route('retailer.index')}}">
                        <span class="icon-holder"><i class="c-light-blue-500 fa fa-group"></i> </span>
                        <span class="title">Retailer</span>
                    </a>
                </li>
            @endif

            @if (isThisMenuActive('bpromoter.index'))
                <li class="nav-item">
                    <a class="sidebar-link" href="{{route('bpromoter.index')}}">
                        <span class="icon-holder"><i class="c-light-blue-500 fa fa-user"></i> </span>
                        <span class="title">Brand Promoter</span>
                    </a>
                </li>
            @endif

            @if (isThisMenuActive('bp-leave-management'))
                <li class="nav-item">
                    <a class="sidebar-link" href="{{route('bp-leave-management')}}">
                        <span class="icon-holder"><i class="c-light-blue-500 fa fa-book"></i> </span>
                        <span class="title">BP Leave Management</span>
                    </a>
                </li>
            @endif

            @if (isThisMenuActive('bpromoter.focus_model_to_bp'))
                <li class="nav-item">
                    <a class="sidebar-link" href="{{route('bpromoter.focus_model_to_bp')}}">
                        <span class="icon-holder"><i class="c-light-blue-500 fa fa-user"></i> </span>
                        <span class="title">Focus Model To BP</span>
                    </a>
                </li>
            @endif

            @if (isThisMenuActive('incentive.index'))
                <li class="nav-item">
                    <a class="sidebar-link" href="{{route('incentive.index')}}">
                        <span class="icon-holder"><i class="c-light-blue-500 fa fa-cube"></i> </span>
                        <span class="title">Incentive</span>
                    </a>
                </li>
            @endif

            @if (isThisMenuActive('banner.index'))
                <li class="nav-item">
                    <a class="sidebar-link" href="{{route('banner.index')}}">
                        <span class="icon-holder"><i class="c-light-blue-500 fa fa-image"></i> </span>
                        <span class="title">Slider Banner</span>
                    </a>
                </li>
            @endif

            @if (isThisMenuActive('promoOffer.index'))
                <li class="nav-item">
                    <a class="sidebar-link" href="{{route('promoOffer.index')}}">
                        <span class="icon-holder"><i class="c-light-blue-500 fa fa-image"></i> </span>
                        <span class="title">Promo Offer</span>
                    </a>
                </li>
            @endif

            @if (isThisMenuActive('message.index'))
                <li class="nav-item">
                    <a class="sidebar-link" href="{{route('message.index')}}">
                        <span class="icon-holder"><i class="c-light-blue-500 fa fa-envelope"></i> </span>
                        <span class="title">Authority Message</span>
                    </a>
                </li>
            @endif

            @if (isThisMenuActive('retailer.stockForm'))
                <li class="nav-item">
                    <a class="sidebar-link" href="{{route('retailer.stockForm')}}">
                        <span class="icon-holder"><i class="c-light-blue-500 fa fa-suitcase"></i> </span>
                        <span class="title">Stock Management</span>
                    </a>
                </li>
            @endif

            @if (isThisMenuActive('imei.disputeList'))
                <li class="nav-item">
                    <a class="sidebar-link" href="{{route('imei.disputeList')}}">
                        <span class="icon-holder"><i class="c-light-blue-500 fa fa-book"></i> </span>
                        <span class="title">IMEI Dispute List</span>
                    </a>
                </li>
            @endif

            @if (isThisMenuActive('report.pending-order'))
                <li class="nav-item">
                    <a class="sidebar-link" href="{{route('report.pending-order')}}">
                        <span class="icon-holder"><i class="c-light-blue-500 fa fa-shopping-cart"></i> </span>
                        <span class="title">Pending Order</span>
                    </a>
                </li>
            @endif

            @if (isThisMenuActive('prebooking.index'))
                <li class="nav-item">
                    <a class="sidebar-link" href="{{route('prebooking.index')}}">
                        <span class="icon-holder"><i class="c-light-blue-500 fa fa-book"></i> </span>
                        <span class="title">Pre Booking</span>
                    </a>
                </li>
            @endif

            @if (isThisMenuActive('pushNotification.index'))
                <li class="nav-item">
                    <a class="sidebar-link" href="{{route('pushNotification.index')}}">
                        <span class="icon-holder"><i class="c-light-blue-500 fa fa-envelope"></i> </span>
                        <span class="title">Push Notification</span>
                    </a>
                </li>
            @endif

            @if (isThisMenuActive('employee.stock-check'))
                <li class="nav-item" style="display:none">
                    <a class="sidebar-link" href="{{route('employee.stock-check')}}">
                        <span class="icon-holder"><i class="c-light-blue-500 fa fa-envelope"></i> </span>
                        <span class="title">Stock Check & Send Mail</span>
                    </a>
                </li>
            @endif

            @if (isThisMenuActive('report.dashboard'))
                <li class="nav-item">
                    <a class="sidebar-link" href="{{route('report.dashboard')}}">
                        <span class="icon-holder"><i class="c-light-blue-500 fa fa-book"></i> </span>
                        <span class="title">Report</span>
                    </a>
                </li>
            @endif

            @if (isThisMenuActive('user.index'))
                <li class="nav-item">
                    <a class="sidebar-link" href="{{route('user.index')}}">
                        <span class="icon-holder"><i class="c-light-blue-500 fa fa-group"></i> </span>
                        <span class="title">User</span>
                    </a>
                </li>
            @endif

            @if (isThisMenuActive('user.loginLog'))
                <li class="nav-item">
                    <a class="sidebar-link" href="{{route('user.loginLog')}}" default>
                        <span class="icon-holder"><i class="c-blue-500 fa fa-users"></i> </span>
                        <span class="title">User Log</span>
                    </a>
                </li>
            @endif

            @if (isThisMenuActive('menu'))
                <li class="nav-item" style="display:none">
                    <a class="sidebar-link" href="{{route('menu')}}">
                        <span class="icon-holder"><i class="c-light-blue-500 fa fa-bars"></i> </span>
                        <span class="title">Menu</span>
                    </a>
                </li>
            @endif            
        </ul>
    </div>
</div>