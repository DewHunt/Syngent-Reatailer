<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title></title>
		<style type="text/css">
			hr.dashed {border-top: 1px dashed black;font-weight: bold;margin: 5px 0px;width: 100%;}
			table {background-color: transparent;}
			caption {padding-top: 0px;padding-bottom: 3px;color: #777;text-align: center;}
			th {text-align: left;}
			.tab {width: 100%;max-width: 100%;margin-bottom: 5px;font-size:16px;}
			.tab > thead > tr > th,.tab > tbody > tr > th,.tab > tfoot > tr > th,
			.tab > thead > tr > td,.tab > tbody > tr > td,
			.tab > tfoot > tr > td {padding: 2px;line-height: 1.42857143;vertical-align: top;border-bottom: 0px dashed black;}
			.tab > thead > tr > th {vertical-align: bottom;border-bottom: 0px dashed black;}                        
			.tab > caption + thead > tr:first-child > th,.tab > colgroup + thead > tr:first-child > th,
			.tab > thead:first-child > tr:first-child > th,.tab > caption + thead > tr:first-child > td,
			.tab > colgroup + thead > tr:first-child > td,.tab > thead:first-child > tr:first-child > td {border-top: 0;}
			.tab > tbody + tbody {border-top: 0px dashed black;}
			.tab .tab {background-color: #fff;}
			.tab-condensed > thead > tr > th,.tab-condensed > tbody > tr > th,
			.tab-condensed > tfoot > tr > th,.tab-condensed > thead > tr > td,
			.tab-condensed > tbody > tr > td,.tab-condensed > tfoot > tr > td {padding: 5px;}
			.tab-bordered {border: 1px solid black;}
			.tab-bordered > thead > tr > th,.tab-bordered > tbody > tr > th,
			.tab-bordered > tfoot > tr > th,.tab-bordered > thead > tr > td,
			.tab-bordered > tbody > tr > td,.tab-bordered > tfoot > tr > td {border: 1px solid black;}
			.tab-bordered > thead > tr > th,.tab-bordered > thead > tr > td {border-bottom-width: 2px;}
			.tab-striped > tbody > tr:nth-of-type(odd) {background-color: #f9f9f9;}
			.tab-hover > tbody > tr:hover {background-color: #f5f5f5;}
			table col[class*="col-"] {position: static;display: table-column;float: none;}
			table td[class*="col-"],table th[class*="col-"] {position: static;display: table-cell;float: none;}
			.tab > thead > tr > td.active,.tab > tbody > tr > td.active,.tab > tfoot > tr > td.active,
			.tab > thead > tr > th.active,.tab > tbody > tr > th.active,.tab > tfoot > tr > th.active,
			.tab > thead > tr.active > td,.tab > tbody > tr.active > td,.tab > tfoot > tr.active > td,
			.tab > thead > tr.active > th,.tab > tbody > tr.active > th,.tab > tfoot > tr.active > th {background-color: #f5f5f5;}
			.tab-hover > tbody > tr > td.active:hover,.tab-hover > tbody > tr > th.active:hover,
			.tab-hover > tbody > tr.active:hover > td,.tab-hover > tbody > tr:hover > .active,
			.tab-hover > tbody > tr.active:hover > th {background-color: #e8e8e8;}
			.tab > thead > tr > td.success,.tab > tbody > tr > td.success,
			.tab > tfoot > tr > td.success,.tab > thead > tr > th.success,
			.tab > tbody > tr > th.success,.tab > tfoot > tr > th.success,
			.tab > thead > tr.success > td,.tab > tbody > tr.success > td,
			.tab > tfoot > tr.success > td,.tab > thead > tr.success > th,
			.tab > tbody > tr.success > th,.tab > tfoot > tr.success > th {background-color: #dff0d8;}
			.tab-hover > tbody > tr > td.success:hover,.tab-hover > tbody > tr > th.success:hover,
			.tab-hover > tbody > tr.success:hover > td,.tab-hover > tbody > tr:hover > .success,
			.tab-hover > tbody > tr.success:hover > th {background-color: #d0e9c6;}
			.tab > thead > tr > td.info,.tab > tbody > tr > td.info,
			.tab > tfoot > tr > td.info,.tab > thead > tr > th.info,
			.tab > tbody > tr > th.info,.tab > tfoot > tr > th.info,
			.tab > thead > tr.info > td,.tab > tbody > tr.info > td,
			.tab > tfoot > tr.info > td,.tab > thead > tr.info > th,
			.tab > tbody > tr.info > th,.tab > tfoot > tr.info > th {background-color: #d9edf7;}
			.tab-hover > tbody > tr > td.info:hover,.tab-hover > tbody > tr > th.info:hover,
			.tab-hover > tbody > tr.info:hover > td,.tab-hover > tbody > tr:hover > .info,
			.tab-hover > tbody > tr.info:hover > th {background-color: #c4e3f3;}
			.tab > thead > tr > td.warning,.tab > tbody > tr > td.warning,
			.tab > tfoot > tr > td.warning,.tab > thead > tr > th.warning,
			.tab > tbody > tr > th.warning,.tab > tfoot > tr > th.warning,
			.tab > thead > tr.warning > td,.tab > tbody > tr.warning > td,
			.tab > tfoot > tr.warning > td,.tab > thead > tr.warning > th,
			.tab > tbody > tr.warning > th,.tab > tfoot > tr.warning > th {background-color: #fcf8e3;}
			.tab-hover > tbody > tr > td.warning:hover,.tab-hover > tbody > tr > th.warning:hover,
			.tab-hover > tbody > tr.warning:hover > td,.tab-hover > tbody > tr:hover > .warning,
			.tab-hover > tbody > tr.warning:hover > th {background-color: #faf2cc;}
			.tab > thead > tr > td.danger,.tab > tbody > tr > td.danger,
			.tab > tfoot > tr > td.danger,.tab > thead > tr > th.danger,
			.tab > tbody > tr > th.danger,.tab > tfoot > tr > th.danger,
			.tab > thead > tr.danger > td,.tab > tbody > tr.danger > td,
			.tab > tfoot > tr.danger > td,.tab > thead > tr.danger > th,
			.tab > tbody > tr.danger > th,.tab > tfoot > tr.danger > th {background-color: #f2dede;}
			.tab-hover > tbody > tr > td.danger:hover,.tab-hover > tbody > tr > th.danger:hover,
			.tab-hover > tbody > tr.danger:hover > td,.tab-hover > tbody > tr:hover > .danger,
			.tab-hover > tbody > tr.danger:hover > th {background-color: #ebcccc;}
			.tab-responsive {min-height: .01%;overflow-x: auto;}
			.tab>tfoot>tr>td {border-bottom: hidden;}
			.tab>tfoot>tr>th {border-bottom: 1px dashed black;}
			.div-separator {border: 1px dashed black;}
			.separator {border-bottom: 1px dashed black;}
			.text-right { text-align: right; }
			.text-center { text-align: center; }
			.item-qty { text-align: right; width: 6%; }
			.item-price { text-align: right; width: 7%; }
		</style>
	</head>

	<body>
	
		<div class="create-print-image" style="width: 300px;">
<table class="tab">
	<tbody>
		<tr>
			<td class="text-center">
				<img width="100px" height="100px" src="<?= asset('public/admin/images/syngenta_logo.png') ?>">
			</td>
		</tr>
		<tr><td class="text-center"><b>Retailer Name</b></td></tr>
		<tr><td class="text-center"><b>Retailer Address</b></td></tr>
		<tr><td class="text-center"><b>Retailer Phone</b></td></tr>
	</tbody>
</table>

<div class="div-separator"></div>

<table class="tab">
	<tbody>
		<tr>
			<td width="35%">
				Date : {{ date('Y-m-d H:i:s') }}<br>
				Print Time : {{ date('Y-m-d H:i:s') }}<br>
				Customer Name: Mr. Rahim<br>
				Customer Phone: 01317243494<br>
			</td>
		</tr>
	</tbody>
</table>

<div class="div-separator"></div>

<table class="tab">
	<caption class="separator"><b>Order Information</b></caption>
	<tbody>
		<tr class="separator">
			<td>Item Name</td>
			<td class="item-qty">Qty</td>
			<td class="item-price">Price</td>
		</tr>
		@php
			$order_total = 0;
			$total_vat = 0;
			$cartContents = array(
				['name' => 'ডেনিম ফিট ৫০ ডব্লিউজি','qty'=>'5','price'=>'20000'],
				['name' => 'একতারা ২৫ ডব্লিউজি','qty'=>'2','price'=>'22500'],
				['name' => 'রিডোমিল গোল্ড এমজেড ৬৮ ডব্লিউজি','qty'=>'21','price'=>'5000'],
			);
		@endphp
		@foreach ($cartContents as $content)
			@php
				$item_total = $content['qty'] * $content['price'];
				$order_total = $order_total + $item_total;
			@endphp
		    <tr>
			    <td>{{ $content['name'] }}</td>
			    <td align="right">{{ $content['qty'] }}</td>
			    <td align="right">{{ $item_total }}</td>
		    </tr>
		@endforeach
	</tbody>

	<tfoot>
		<tr>
			<!--td >*Vat Included</td-->
			<td colspan="2" align="right">Total : </td>
			<td align="right">{{ number_format($order_total,2,'.','') }}</td>
		</tr>
	</tfoot>
</table>

<div class="div-separator"></div>

<table class="tab">
	<tbody>
		<tr><td align="center">Thanks For Purchasing</td></tr>
	</tbody>
</table>

<div class="div-separator"></div>

</div>

<!--

		<script src="{{asset('public/admin/js/jquery-3.6.0.js')}}"></script>
		<script type="text/javascript" src="{{asset('public/admin/js/jquery.min.js')}}"></script>
		<script src="{{ asset('public/admin/js/html2canvas.js') }}"></script>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				createPrintImage();
			});
			var APP_URL = {!! json_encode(url('/')) !!};
			jQuery.ajaxSetup({
			    headers: {
			        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
			    }
			});

			function createPrintImage() {
		        // var getBrandId              = jQuery('#brand_id').val(); 
		        // var getBrandName            = jQuery('#brandName').text();
		        // var prescriptionImageName   = getBrandName.toLowerCase().replace(/\s/g, '');

alert('fysiufysi');


		        html2canvas(jQuery(".create-print-image"), {
		            onrendered: function(canvas) {
		                theCanvas = canvas;
		                document.body.appendChild(canvas);
		                
		                //change the canvas to jpeg image
		                data = canvas.toDataURL('image/png');
		                save_img(data);
		                
		                canvas.toBlob(function(blob) {
		                    //saveAs(blob, prescriptionImageName+".png");
		                });
		            }
		        });
			}

			function save_img(data) {
			    console.log(data);
			    let _token  = jQuery('meta[name="csrf-token"]').attr('content');
			    jQuery.ajax({
			        url:APP_URL+'/api/sales/save-invoice-view',
			        type:"POST",
			        dataType:'JSON',
			        data:{printDivPhoto:data,_token: _token},
			        success:function(response) {
			            console.log(response);
			            if(response == 'success') {
			                Notiflix.Notify.Success('Brand Save Successfully');
			                window.location = "{{url('create-brand')}}";
			            }
			            if(response == 'error') {
			                Notiflix.Notify.Failure('Brand Save Failed');
			            }
			        }
			    });
			}
		</script>  -->
	</body>
</html>
