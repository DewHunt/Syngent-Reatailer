<?php $segment = Request::segment(1); ?>

@if (isThisMenuActive('report.bp-sales'))
    <a href="{{ route('report.bp-sales') }}" class="list-group-item {{ $segment == 'report.bp-sales' || $segment == 'bpDateRangesalesReport' || $segment == 'bpSaleOrderDetails' ? 'active':'inactive' }}">
        <span class="icon-holder"><i class="c-light-blue-000 fa fa-check-square"></i></span>&nbsp;BP Sales Reports
    </a>
@endif

@if (isThisMenuActive('report.sales-invoice'))
    <a href="{{ route('report.sales-invoice') }}" class="list-group-item {{ $segment == 'report.sales-invoice' || $segment == 'dateRangesalesReport' || $segment == 'SaleOrderDetails' ? 'active':'inactive' }}">
        <span class="icon-holder"><i class="c-light-blue-000 fa fa-check-square"></i></span>&nbsp;Sales Invoice
    </a>
@endif

@if (isThisMenuActive('report.incentive'))
    <a href="{{ route('report.incentive') }}" class="list-group-item {{ $segment == 'report.incentive' || $segment == 'incentive_report' || $segment == 'SaleIncentiveDetails' ? 'active':'inactive' }}">
        <span class="icon-holder"><i class="c-light-blue-000 fa fa-check-square"></i></span>&nbsp;Sales Incentive Reports
    </a>
@endif

@if (isThisMenuActive('report.bp-attendance'))
    <a href="{{ route('report.bp-attendance') }}" class="list-group-item {{ $segment == 'report.bp-attendance' || $segment == 'bp_attendance_report' || $segment == 'bpAttendanceDetails' ? 'active':'inactive' }}">
        <span class="icon-holder"><i class="c-light-blue-000 fa fa-check-square"></i></span>&nbsp;BP Attendance Reports
    </a>
@endif

@if (isThisMenuActive('report.bp-leave'))
    <a href="{{ route('report.bp-leave') }}" class="list-group-item {{ $segment == 'report.bp-leave' || $segment == 'bp_leave_report' ? 'active':'inactive' }}">
        <span class="icon-holder"><i class="c-light-blue-000 fa fa-check-square"></i></span>&nbsp;BP Leave Reports
    </a>
@endif

@if (isThisMenuActive('report.imei-sold'))
    <a href="{{ route('report.imei-sold') }}" class="list-group-item {{ $segment == 'report.imei-sold' || $segment == 'report.soldimei-search-report' ? 'active':'inactive' }}">
        <span class="icon-holder"><i class="c-light-blue-000 fa fa-check-square"></i></span>&nbsp;IMEI Sold Reports
    </a>
@endif

@if (isThisMenuActive('report.sales-product'))
    @if (isThisMenuActive('report.sales-product'))
        <a href="{{ route('report.sales-product') }}" class="list-group-item {{ $segment == 'report.sales-product' ||  $segment == 'modelSalesReport' ||  $segment == 'modelSalesReportDetails' ? 'active':'inactive' }}">
            <span class="icon-holder"><i class="c-light-blue-000 fa fa-check-square"></i></span>&nbsp;Product Sales Report
        </a>
    @endif
@endif

@if (isThisMenuActive('report.pre-booking'))
    <a href="{{ route('report.pre-booking') }}" class="list-group-item {{ $segment == 'report.pre-booking' || $segment == 'preBookingReport' ? 'active':'inactive' }}">
        <span class="icon-holder"><i class="c-light-blue-000 fa fa-check-square"></i></span>&nbsp;Pre-Booking Order Report
    </a>
@endif

@if (isThisMenuActive('report.pending-bounce-order'))
    <a href="{{ route('report.pending-bounce-order') }}" class="list-group-item {{ $segment == 'report.pending-bounce-order' || $segment == 'pendingOrderReport' || $segment == 'report.pending-search-report' ? 'active':'inactive' }}">
        <span class="icon-holder"><i class="c-light-blue-000 fa fa-check-square"></i></span>&nbsp;Pending Order Report
    </a>
@endif

@if (isThisMenuActive('report.imei-dispute-report'))
    <a href="{{ route('report.imei-dispute-report') }}" class="list-group-item {{ $segment == 'report.imei-dispute-report' || $segment == 'report.search-imei-dispute-report' ? 'active':'inactive' }}">
        <span class="icon-holder"><i class="c-light-blue-000 fa fa-check-square"></i></span>&nbsp;IMEI Dispute Report
    </a>
@endif

@if (isThisMenuActive('report.incentive-list'))
    <a href="{{ route('report.incentive-list') }}" class="list-group-item {{ $segment == 'report.incentive-list' || $segment == 'report.search_incentive' ? 'active':'inactive' }}">
        <span class="icon-holder"><i class="c-light-blue-000 fa fa-check-square"></i></span>&nbsp;Incentive List Reports
    </a>
@endif

@if (isThisMenuActive('report.get-sales-return'))
    <a href="{{ url('report.get-sales-return') }}" class="list-group-item {{ $segment == 'report.get-sales-return' ? 'active':'inactive' }}">
        <span class="icon-holder"><i class="c-light-blue-000 fa fa-check-square"></i></span>&nbsp;Sales Return Reports
    </a>
@endif
