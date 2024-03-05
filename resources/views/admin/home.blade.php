@extends('admin.master.master')
@section('page-style')
	<link href="{{asset('public/admin/css/components.min.css')}}" rel="stylesheet" type="text/css">	
	<style>
	    a { color:#000000 !important; }
	    @media (min-width: 768px) {
	        .user-top-activity { margin-top: 2% !important; }
	        .bp-top-margin { margin-top: 1% !important; }
	    }
	    /* Portrait and Landscape */
	    @media only screen 
	    and (min-device-width: 320px) 
	    and (max-device-width: 568px)
	    and (-webkit-min-device-pixel-ratio: 2) {
	        .bp-top-margin { margin-top: 22% !important; }
	        .user-top-activity { position: relative !important; margin-top: 0% !important; }
	        .bp-top-margin .col-md-6 { -ms-flex: 0 0 100% !important; flex: 0 0 100% !important; max-width: 100% !important; }
	        .bp-top-height { display: block !important; }
	    }
	    /* Portrait and Landscape */
	    @media only screen 
	    and (min-device-width: 414px) 
	    and (max-device-width: 736px) 
	    and (-webkit-min-device-pixel-ratio: 3) { 
	        .bp-top-margin { margin-top: 15% !important; }
	    }
	    /* Portrait and Landscape */
	    @media only screen 
	    and (min-device-width: 375px) 
	    and (max-device-width: 812px) 
	    and (-webkit-min-device-pixel-ratio: 3) { 
	        .bp-top-margin { margin-top: 22% !important; }
	        .user-top-activity { position: relative !important; margin-top: 0% !important; }
	        .bp-top-margin .col-md-6 { -ms-flex: 0 0 100% !important; flex: 0 0 100% !important; max-width: 100% !important; }
	        .bp-top-height { display: block !important; }
	    }
	    @media (min-width: 768px) and (max-width: 1024px) {
	        .loginDipMt { margin-top: 500px; }
	        .bp-top-margin { margin-top: 1% !important; }
	        .user-top-activity { position: relative !important; margin-top: 0% !important; }
	        .bp-top-margin .col-md-6 { -ms-flex: 0 0 100% !important; flex: 0 0 100% !important; max-width: 100% !important; }
	        .bp-top-height { display: block !important; }
	    }
		.highcharts-container, .highcharts-container svg { width: 100% !important; }
		.wrapper { display: table; width: 100%; background-color:#ffffff; height:400px; }
		.element { display: table-cell; width: 100%; }
		.element h1{ color: #333333; font-size: 18px; fill: #333333; }
		.legend { display: table-cell; width: 50%; vertical-align: middle; }
		#monthlySalesChart { width: 100%; }
	</style>
@endsection

@section('content')
	@php
		$loginUserEmail = $loginUserEmpId = $loginUserPassword = $loginUserRemember = "";
		if (isset($_COOKIE["loginUserEmail"]))  { 
		    $loginUserEmail =  $_COOKIE["loginUserEmail"]; 
		} 
		if (isset($_COOKIE["loginUserEmpId"])) { 
		    $loginUserEmpId = $_COOKIE["loginUserEmpId"]; 
		}
		if (isset($_COOKIE["loginUserPassword"])) { 
		    $loginUserPassword =  $_COOKIE["loginUserPassword"]; 
		}
		if (isset($_COOKIE["loginUserRemember"])) { 
		    $loginUserRemember =  $_COOKIE["loginUserRemember"]; 
		}  
	@endphp

	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
		<div class="wrapper">
			<div class="element">
				<div id="loading1" class="text-center" >
					<h1>Daily Sales Report</h1>
					<img src="{{ asset('public/loader1.gif') }}"/>
				</div>
				<div id="monthlySalesChart"></div>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>

	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mT-30">
		<div class="wrapper">
			<div class="element">
				<div id="loading2" class="text-center" >
					<h1>Monthly Sales Report</h1>
					<img src="{{ asset('public/loader1.gif') }}"/>
				</div>
				<div id="yearMonthlySalesList"></div>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>

	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mT-30">
		<div class="row">
			{{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
				<div class="bgc-white p-20">
					<div id="loading3" class="text-center">
						<h4>BP Top 10 Saler List</h4>
						<img src="{{asset('public/loader1.gif')}}"/>
					</div>
					<div id="bpTop">
						<h6 class="c-grey-900"><strong>BP Top 10 Saler List</strong></h6>
						<div class="mT-30"><canvas id="bpTopSaller" height="220"></canvas></div>
					</div>
				</div>
			</div> --}}
			{{-- <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="bgc-white p-20">
					<div id="loading4" class="text-center" >
						<h4>Retailer Top 10 Saler List</h4>
						<img src="{{asset('public/loader1.gif')}}"/>
					</div>
					<div id="retailerTop">
						<h6 class="c-grey-900"><strong>Retailer Top 10 Saler List</strong></h6>
						<div class="mT-30"><canvas id="retailerTopSaller" height="220"></canvas></div>
					</div>
				</div>
			</div> --}}
		</div>
	</div>
	<div class="clearfix"></div>

	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mT-30">
		<div class="wrapper">
			<div class="element">
				<div id="loading6" class="text-center" >
					<h1>Brand-Wise Report</h1>
					<img src="{{asset('public/loader1.gif')}}"/>
				</div>
				<div id="pie-chart"></div>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>

	@php
		$getPermissionMenuId = explode(',',Auth::getUser()->permission_menu_id);
		$permissionStatus = (in_array(73, $getPermissionMenuId)) ? 1 : 0;
	@endphp

	@if($permissionStatus == 1)
		<div class="masonry-item col-md-12 user-top-activity mT-30">
		    <div class="bgc-white p-20 bd">
		        <h4 class="c-grey-900 mB-20">User Login Activity <a href="{{url('user.loginLog')}}"></h4>
		        <div class="table-responsive">
		            <table class="table table-bordered table-sm table-striped" style="width: 100%;">
		                <thead class="text-center">
		                    <tr>
		                        <th scope="col">Sl.</th>
		                        <th scope="col">Name</th>
		                        <th scope="col">Event Type</th>
		                        <th scope="col">User Agent</th>
		                        <th scope="col">IP Address</th>
		                        <th scope="col">Date</th>
		                    </tr>
		                </thead>
		                <tbody>
		                    @foreach($loginLogList as $activity)
		                    <tr>
		                        <th scope="row">{{ ++$loop->index }}.</th>
		                        <td>{{ $activity->name }}</td>
		                        <td>{{ $activity->type }}</td>
		                        <td>{{ $activity->user_agent }}</td>
		                        <td>{{ $activity->ip_address }}</td>
		                        <td><span class="c-green-500">{{ date('d M Y h:i:s a',strtotime($activity->created_at)) }}</span></td>
		                    </tr>
		                    @endforeach
		                </tbody>
		            </table>
		            <a href="{{route('user.loginLog')}}">
		                <button type="button" class="btn btn-primary cur-p btn-sm pull-right">View All</button>
		            </a>
		        </div>
		    </div>
		</div>
	@endif
@endsection

@section('page-scripts')
	<!--Multi Chart js Integration Start Here-->
	<script src="{{ asset('public/admin/js/highcharts/highcharts.js') }}"></script>
	<script src="{{ asset('public/admin/js/highcharts/exporting.js') }}"></script>
	<script src="{{ asset('public/admin/js/highcharts/export-data.js') }}"></script>
	<script src="{{ asset('public/admin/js/highcharts/accessibility.js') }}"></script>

	<script type="text/javascript">
		$(document).ready(function(){
			daily_sales_report_chart();
			monthly_sales_report_chart();
			// bp_top_ten_saler_report_chart();
			// retailer_top_ten_saler_report_chart();
			model_wise_sales_report_chart();
		});

		function daily_sales_report_chart() {
			jQuery.ajax({
		        url:"{{ route('get_daily_sales_report') }}",
		        type:"GET",
		        dataType:"JSON",
				async:true,
				beforeSend: function() { $("#loading1").show(); },
				success:function(response) {
		            //console.log(JSON.parse(response.salesDate));
					//console.log(JSON.parse(response.salesQty));
					$("#loading1").hide();
					var salesQty = JSON.parse(response.salesQty);
					Highcharts.chart('monthlySalesChart', {
						title: { text: 'Daily Sales Report' },
						xAxis: { categories:JSON.parse(response.salesDate) },
						yAxis: {
							title: { text: 'Number of Sales Quantity' }
						},
						legend: { layout: 'vertical', align: 'right', verticalAlign: 'middle' },
						plotOptions: {
							series: { allowPointSelect: true }
						},
						series: [{ name: 'Quantity', data: salesQty }],
						responsive: {
							rules: [{
								condition: { maxWidth: 500 },
								chartOptions: {
									legend: { layout: 'horizontal', align: 'center', verticalAlign: 'bottom' }
								}
							}]
						}
					});
				},
		        error:function(error) {
				}
			});
		}

		function bp_top_ten_saler_report_chart() {
			jQuery.ajax({
		        url:"{{ route('get_bp_top_saler') }}",
		        type:"GET",
		        dataType:"JSON",
				async:true,
				beforeSend: function() { $("#loading3").show(); $("#bpTop").hide(); },
				success:function(response) {
					$("#loading3").hide();
					$("#bpTop").show();
					//console.log(JSON.parse(response.bpName));
					//console.log(JSON.parse(response.bpAmount));
					var ctx = document.getElementById('bpTopSaller').getContext('2d');
					var myChart = new Chart(ctx, {
					    type: 'bar',
					    data: {
					        labels:  JSON.parse(response.bpName), //['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
					        datasets: [{
					            label: '# Amount',
					            data:  JSON.parse(response.bpAmount), //[12, 19, 3, 5, 2, 3],
					            backgroundColor: [
					                'rgba(255, 99, 132, 0.2)',
					                'rgba(54, 162, 235, 0.2)',
					                'rgba(255, 206, 86, 0.2)',
					                'rgba(75, 192, 192, 0.2)',
					                'rgba(153, 102, 255, 0.2)',
					                'rgba(255, 159, 64, 0.2)'
					            ],
					            borderColor: [
					                'rgba(255, 99, 132, 1)',
					                'rgba(54, 162, 235, 1)',
					                'rgba(255, 206, 86, 1)',
					                'rgba(75, 192, 192, 1)',
					                'rgba(153, 102, 255, 1)',
					                'rgba(255, 159, 64, 1)'
					            ],
					            borderWidth: 1
					        }],
					    },
					    options: {
					        layout: {
					          padding: { left: 0, right: 0, top: 15, bottom: 0 }
					        },
					        events: [],
					        responsive: true,
					        maintainAspectRatio: true,
					        legend: { display: true },
					        scales: {
					          yAxes: [{
					            ticks: { beginAtZero: true, display: true }
					          }]
					        },
					        animation: {
					        	duration: 1,
					        	onComplete: function() {
					        		var chartInstance = this.chart, ctx = chartInstance.ctx;
					        		ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
					        		ctx.textAlign = 'center';
					        		ctx.textBaseline = 'bottom';
					        		this.data.datasets.forEach(function(dataset, i) {
					        			var meta = chartInstance.controller.getDatasetMeta(i);
					        			meta.data.forEach(function(bar, index) {
					        				if (dataset.data[index] > 0) {
					        					var data = dataset.data[index];
					        					ctx.fillText(data, bar._model.x, bar._model.y);
					        				}
					        			});
					        		});
					        	}
					        },
					    }
					});
				}
			});
		}

		function retailer_top_ten_saler_report_chart() {
			var ctx = document.getElementById('retailerTopSaller').getContext('2d');
			jQuery.ajax({
		        url:"{{ route('get_retailer_top_saler') }}",
		        type:"GET",
		        dataType:"JSON",
				async:true,
				beforeSend: function() { $("#loading4").show(); $("#retailerTop").hide(); },
				success:function(response) {
					$("#loading4").hide();
					$("#retailerTop").show();
					//console.log(JSON.parse(response.retailerName));
					//console.log(JSON.parse(response.retailerAmount));					
					
					var myChart = new Chart(ctx, {
						type: 'bar',
						data: {
							labels: JSON.parse(response.retailerName),
							datasets: [{
								label: '# Amount',
								data: JSON.parse(response.retailerAmount),
								backgroundColor: [
									'rgba(255, 99, 132, 0.2)',
									'rgba(54, 162, 235, 0.2)',
									'rgba(255, 206, 86, 0.2)',
									'rgba(75, 192, 192, 0.2)',
									'rgba(153, 102, 255, 0.2)',
									'rgba(255, 159, 64, 0.2)'
								],
								borderColor: [
									'rgba(255, 99, 132, 1)',
									'rgba(54, 162, 235, 1)',
									'rgba(255, 206, 86, 1)',
									'rgba(75, 192, 192, 1)',
									'rgba(153, 102, 255, 1)',
									'rgba(255, 159, 64, 1)'
								],
								borderWidth: 1
							}],
						},
						options: {
							layout: {
							  padding: { left: 0, right: 0, top: 15, bottom: 0 }
							},
							events: [],
							responsive: true,
							maintainAspectRatio: true,
							legend: { display: true },
							scales: {
							  yAxes: [{
								ticks: { beginAtZero: true, display: true }
							  }]
							},
							animation: {
								duration: 1,
								onComplete: function() {
									var chartInstance = this.chart, ctx = chartInstance.ctx;
									ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
									ctx.textAlign = 'center';
									ctx.textBaseline = 'bottom';
									this.data.datasets.forEach(function(dataset, i) {
										var meta = chartInstance.controller.getDatasetMeta(i);
										meta.data.forEach(function(bar, index) {
											if (dataset.data[index] > 0) {
												var data = dataset.data[index];
												ctx.fillText(data, bar._model.x, bar._model.y);
											}
										});
									});
								}
							},
						}
					});
				},
		        error:function(error) {
				}
			});
		}

		function model_wise_sales_report_chart() {
			jQuery.ajax({
		        url:"{{ route('get_model_waise_report') }}",
		        type:"GET",
		        dataType:"JSON",
				async:true,
				beforeSend: function() { $("#loading6").show(); },
				success:function(response) {
					$("#loading6").hide();
					$(function() {
				        Highcharts.chart('pie-chart', {
				            chart: {
				                plotBackgroundColor: null,
				                plotBorderWidth: null,
				                plotShadow: false,
				                type: 'pie'
				            },
				            title: { text: 'Brand-Wise Report' },
				            tooltip: { pointFormat: '{series.name}: <b>{point.y}</b>' },
				            accessibility: {
				                point: { valueSuffix: '%' }
				            },
				            plotOptions: {
				                pie: {
				                    allowPointSelect: true,
				                    cursor: 'pointer',
				                    dataLabels: { enabled: true, format: '<b>{point.name}</b>: {point.y} ' }
				                }
				            },
				            series: [{ name: 'Sale Qty', colorByPoint: true, data: JSON.parse(response.data) }]
				        });
				    });
				}
			});
		}

		function monthly_sales_report_chart() {
			jQuery.ajax({
		        url:"{{ route('get_monthly_sales_report') }}",
		        type:"GET",
		        dataType:"JSON",
				async:true,
				beforeSend: function() { $("#loading2").show(); },
				success:function(response) {
					$("#loading2").hide();
					var monthSalesQty =  JSON.parse(response.yearMonthQty);
					Highcharts.chart('yearMonthlySalesList', {
						title: { text: 'Monthly Sales Report' },
						xAxis: { categories: JSON.parse(response.yearMonthNameList) },
						yAxis: {
							title: { text: 'Monthly Number of Sales Quantity' }
						},
						legend: { layout: 'vertical', align: 'right', verticalAlign: 'middle' },
						plotOptions: {
							series: { allowPointSelect: true }
						},
						series: [{ name: 'Quantity', data: monthSalesQty }],
						responsive: {
							rules: [{
								condition: { maxWidth: 500 },
								chartOptions: {
									legend: { layout: 'horizontal', align: 'center', verticalAlign: 'bottom' }
								}
							}]
						}
					});
				},
				error:function(error) {
				}
			});
		}
	</script>
@endsection


