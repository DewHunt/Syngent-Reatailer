@extends('admin.master.master')

@section('page-style')
	<style>
		.common-btn-width { width: 150px; }
	    @media only screen 
	    and (min-device-width: 320px) 
	    and (max-device-width: 568px)
	    and (-webkit-min-device-pixel-ratio: 2) {
	        .bgc-white .btn { padding: 1rem 1rem !important; font-size: 2rem !important; width: 330px; height: 80px; }
	    }
	    @media only screen 
	    and (min-device-width: 375px) 
	    and (max-device-width: 812px) 
	    and (-webkit-min-device-pixel-ratio: 3) { 
	        .bgc-white .btn { padding: 1rem 1rem !important; font-size: 2rem !important; width: 330px; height: 80px; }
	    }
	    @media (min-width: 768px) and (max-width: 1024px) {
	        .bgc-white .btn { padding: 1rem 1rem !important; font-size: 2rem !important; width: 330px; height: 80px; }
	    }
	</style>
@endsection

@section('content')
	<h4 class="c-grey-900 mB-20">Incentive & Special Award</h4>
	@include('admin.incentive.menu')
@endsection