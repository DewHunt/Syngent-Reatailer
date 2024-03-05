<script src="{{asset('public/admin/js/jquery-3.6.0.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/jquery.min.js')}}"></script>

<!--PDF & Excel Formation data export start -->
<script type="text/javascript" src="{{asset('public/admin/js/pdfmake.min.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/html2canvas.min.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/xlsx.full.min.js')}}"></script>
<!--PDF & Excel Formation data export End -->

<!-- Latest compiled JavaScript -->
<script type="text/javascript" src="{{asset('public/admin/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/bootstrap-datetimepicker.min.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/vendor.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/bundle.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/beacon.min.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/notiflix-1.9.1.js')}}"></script>
<script type="text/javascript" charset="utf8" src="{{asset('public/admin/js/jquery.dataTables.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/bootstrap-toggle.min.js')}}"></script>
<!-- bootstrap datepicker -->
<script type="text/javascript" src="{{ asset('public/admin/js/jquery-ui.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/admin/js/bootstrap-datepicker.min.js') }}"></script>

<!-- Select2 -->
<script type="text/javascript" src="{{ asset('public/admin/select2/js/select2.full.min.js') }}"></script>

<!-- DataTables & Plugins -->
<script src="{{ asset('public/admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/admin/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('public/admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/admin/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('public/admin/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('public/admin/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('public/admin/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('public/admin/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('public/admin/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

{{-- Daterange Picker --}}
<script src="{{ asset('public/admin/datepicker/js/moment.js') }}"></script>
<script src="{{ asset('public/admin/datepicker/js/daterangepicker.js') }}"></script>

{{-- Datatable Font JS --}}
<script src="{{ asset('public/admin/js/vfs_fonts.js') }}"></script>

<script type="text/javascript">
    var APP_URL = {!! json_encode(url('/')) !!};

    jQuery(document).ready(function(){
        pdfMake.fonts = {
            Roboto: {
                normal: 'Roboto-Regular.ttf',
                bold: 'Roboto-Medium.ttf',
                italics: 'Roboto-Italic.ttf',
                bolditalics: 'Roboto-MediumItalic.ttf'
            },
            SolaimanLipi: {
                normal: "SolaimanLipi.ttf",
                bold: "SolaimanLipi.ttf",
                italics: "SolaimanLipi.ttf",
                bolditalics: "SolaimanLipi.ttf"
            },
            Nikosh: {
                normal: "Nikosh.ttf",
                bold: "Nikosh.ttf",
                italics: "Nikosh.ttf",
                bolditalics: "Nikosh.ttf"
            }
        };
        jQuery('.agentDiv').hide();
        jQuery('.bankDiv').hide();
    });

    // Daterange Picker Required JS Start
    var start = moment();
    var end = moment();
    show_daterange(start,end);

    function set_date(start, end) {
        jQuery('#daterange-div .date-input-div input[name="from_date"]').val(start.format('YYYY-MM-DD'));
        jQuery('#daterange-div .date-input-div input[name="to_date"]').val(end.format('YYYY-MM-DD'));
        jQuery('#daterange-div span').html(start.format('MMMM D, YYYY')+' - '+end.format('MMMM D, YYYY'));
    }

    function show_daterange(start,end) {
        console.log('Start Date',start);
        console.log('End Date',end);
        jQuery('#daterange-div').daterangepicker({
           startDate: start,
           endDate: end,
           ranges: {
              'Today': [moment(), moment()],
              'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
              'Last 7 Days': [moment().subtract(6, 'days'), moment()],
              'Last 30 Days': [moment().subtract(29, 'days'), moment()],
              'This Month': [moment().startOf('month'), moment().endOf('month')],
              'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
           }
        }, set_date);
        set_date(start, end);
    }
    // Daterange Picker Required JS Start

    jQuery(function () {
        jQuery("#example1").DataTable({
            "responsive": true,
            "paging": true,
            "pageLength" :2,
            "lengthChange": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "buttons": ["excel", "pdf", "print"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        jQuery('#example2').DataTable({
            "pageLength": 100,
            "paging": true,
            "lengthChange": false,
            // 'lengthMenu' : [[100, 500, 1000, -1], [100, 500, 1000, "All"]],
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
        jQuery("#example3").DataTable({
            "responsive": true,
            "searching": true,
            "paging": true,
            "pageLength" :100,
            "lengthChange": false,
            "ordering": false,
            "autoWidth": true,
            "buttons": [
                {
                    extend: 'excel',
                    title: 'sngenta_retail_management_system',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    },
                },
                // {
                //     extend: 'pdf',
                //     title: 'sngenta_retail_management_system',
                //     exportOptions: {
                //         columns: "thead th:not(.noExport)"
                //     },
                //     customize: function(doc) {
                //         doc.defaultStyle.font = "Nikosh";
                //     }
                // },
                {
                    extend: 'print',
                    title: 'sngenta_retail_management_system',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                },
            ],
        }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');
        jQuery('#attendanceData').DataTable({
            "pageLength": 500,
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": false,
            "responsive": false,
            "buttons": ["excel", "pdf", "print"]
        }).buttons().container().appendTo('#attendanceData_wrapper .col-md-6:eq(0)');
        jQuery("#example4").DataTable({
            "responsive": true,
            "searching": true,
            "paging": true,
            "pageLength" :500,
            "lengthChange": false,
            "ordering": false,
            "autoWidth": true,
            "buttons": ["excel"]
        }).buttons().container().appendTo('#example4_wrapper .col-md-6:eq(0)');
    });
</script>

<!--PDF & Excel Formation data export Start -->
<script>
var today = new Date();
var dd = String(today.getDate()).padStart(2, '0');
var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
var yyyy = today.getFullYear();
var today = mm + '/' + dd + '/' + yyyy;

function ExportToExcel(type, fn, dl) {
    var elt = document.getElementById('dataExport');
    var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
    return dl ?
        XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
        XLSX.writeFile(wb, fn || ('ExportExcel_'+today+'.'+ (type || 'xlsx')));
}

jQuery("body").on("click", "#btnPdf", function (e) {
    e.preventDefault();
    html2canvas($('#dataExport')[0], {
        onrendered: function (canvas) {
            var data = canvas.toDataURL();
            var docDefinition = {
                content: [{
                    image: data,
                    width: 500
                }]
            };
            pdfMake.createPdf(docDefinition).download("ExportPdf_"+today+".pdf");
        }
    });
});
</script>
<!--PDF & Excel Formation data export End -->
<script>
jQuery.noConflict();
jQuery(document).ready(function() {
    jQuery('.CustomdataTable').dataTable({
        "aLengthMenu": [[100, 200, 500, 1000, -1], [100, 200, 500, 1000, "All"]],
        "iDisplayLength": 100,
        "bPaginate": false,
        "bInfo":false
    });
    //Date picker
    jQuery('.datepicker').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });
    //jQuery('#example').DataTable();
    //Initialize Select2 Elements
    jQuery('.select2').select2();

    //Initialize Select2 Elements
    jQuery('.select2bs4').select2({
      theme: 'bootstrap4'
    });
    jQuery('.time').datetimepicker({
        format: 'hh:mm:ss'
    });
});

function checkPaymentType($payment_type) {
    if ($payment_type == 1) {
        var PaymentType = $('#UpdateApiRetailerPaymentType').val();
        var PaymentNumberVal = $('#UpdateApiRetailerPaymentNumber').val();

        $('.paymentNumber').html('<input type="text" name="agent_name" class="form-control mfc_name" placeholder="Enter Agent Name Ex:Bkash,Nogod,Rocket" required=""/><br/><input type="text" name="payment_number" class="form-control mfc_field" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" placeholder="Enter Agent Number Ex: 98586254781" maxlength="11"  minlength="11" required=""/>');

        if(PaymentType == 1) {
            $('.mfc_field').val(PaymentNumberVal);
            $('.bank_field').empty();
            $('.bank_name').empty();
        }

        $('.bank_field').remove();
        $('.bank_name').remove();
    } else {
        var PaymentType = $('#UpdateApiRetailerPaymentType').val();
        var PaymentNumberVal = $('#UpdateApiRetailerPaymentNumber').val();

         $('.paymentNumber').html('<input type="text" name="bank_name" class="form-control bank_name" placeholder="Enter Bank Name Ex:DBBL,Jamuna Bank" required=""/><br/><input type="text" name="payment_number" class="form-control bank_field" placeholder="Bank Account Number Ex:227 103 xxxxx"  minlength="11" required=""/>');

        if (PaymentType == 2) {
            $('.bank_field').val(PaymentNumberVal);
            $('.mfc_field').empty();
            $('.mfc_name').empty();
        }
        $('.mfc_field').remove();
        $('.mfc_name').remove();
    }
}
function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    //if (charCode > 31 && (charCode < 48 || charCode > 57))
    if (!(charCode.shiftKey == false && (charCode == 46 || charCode == 8 || charCode == 37 || charCode == 39 || (charCode >= 48 && charCode <= 57)))) {
        evt.preventDefault();
        alert('Only Number Allowed')
    }

    return true;
}
</script>

<script>
jQuery(function(){
  jQuery.ajaxSetup({
    headers: { 'X-CSRF-Token' : '{{csrf_token()}}' }
  });
});
</script>
<script>
jQuery(document).ready(function(){
    @if($message = Session::get('success'))
        Notiflix.Notify.Success( '{{ $message }}' );
    $('.active_list').addClass("active");
    $('.active_form').removeClass('active');
    @elseif($message = Session::get('error'))
        Notiflix.Notify.Failure( '{{ $message }}' );
    $('.active_form').addClass("active");
    $('.active_list').removeClass('active');
    @elseif($message = Session::get('warning'))
        Notiflix.Notify.Warning('{{ $message }}' );
    @elseif($message = Session::get('info'))
        Notiflix.Notify.Info( '{{ $message }}' );
    //@else
        //Notiflix.Notify.Failure( '{{ $message }}' );              
    @endif
});
</script>
<script type="text/javascript">
jQuery('.Number').keypress(function (event) {
    var keycode = event.which;
    if (!(event.shiftKey == false && (keycode == 46 || keycode == 8 || keycode == 37 || keycode == 39 || (keycode >= 48 && keycode <= 57)))) {
        event.preventDefault();
        alert('Only Number Allowed')
    }
});
// ime Product Data View
jQuery(document).on("click","#viewProductInfo",function(e){
  e.preventDefault();
  var ProductId = jQuery(this).data('id');
  var url = "imeProductDetails"+"/"+ProductId;
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    success:function(response){
        console.log(response);
        Notiflix.Loading.Remove(300);

        if(response == "error") {
            Notiflix.Notify.Failure( 'Data Not Found' );
            setTimeout(function(){// wait for 5 secs(2)
                window.location.reload(); // then reload the page.(3)
                $(".btnCloseModal").click();
            }, 1000);
        }

        if(response) {
            jQuery('.product_model').html(response.product_model);
            jQuery('.product_code').html(response.product_code);
            jQuery('.product_type').html(response.product_type);
            jQuery('.mrp_price').html(response.mrp_price);
            jQuery('.msdp_price').html(response.msdp_price);
            jQuery('.msrp_price').html(response.msrp_price);
        }
    }
  });
});
// Order Details Data View
jQuery(document).on("click","#viewOrderDetails",function(e){
    e.preventDefault();
    $('#salesInfo').html("");
    $('#itemList').html("");
    $('.msrpPrice').html("");
    $('.totalSalePrice').html("");
    $('.totalSaleQty').html("");
    var orderId = jQuery(this).data('id');
    var url = "OrderDetailsView"+"/"+orderId;
    jQuery.ajax({
        url:url,
        type:"GET",
        dataType:"JSON",
        beforeSend: function() {
            Notiflix.Loading.Arrows('Data Processing');
        },
        success:function(response) {
            console.log(response);
            Notiflix.Loading.Remove(300);

            if(response == "error") {
                Notiflix.Notify.Failure( 'Data Not Found' );
                setTimeout(function() {// wait for 5 secs(2)
                    window.location.reload(); // then reload the page.(3)
                    $(".btnCloseModal").click();
                }, 1000);
            }

            if(response) {
                Notiflix.Loading.Remove(100);
                jQuery('#salesInfo').append(response.salesInfo);
                jQuery('#itemList').append(response.itemList);
                jQuery('#saleId').val(response.saleId);
                jQuery('.msrpPrice').append(response.msrpPrice);
                jQuery('.totalSalePrice').append(response.totalSalePrice);
                jQuery('.totalSaleQty').append(response.totalSaleQty);
            }
        }
    });
});
//API Search Product By Id

jQuery('.pending-order-toggle-class').change(function(e) {
e.preventDefault();
    var status      = jQuery(this).prop('checked') == true ? 1 : 0; 
    var orderId     = jQuery(this).data('id');
    var url         = "PendingOrderStatus"+"/"+orderId;
    jQuery.ajax({
        url:url,
        type:"GET",
        dataType:'JSON',
        cache: false,
        contentType: false,
        processData: false,
        success:function(response){
            if(response.success) {
                Notiflix.Notify.Success( 'Order Status Update Successfull' );
            }
            if(response.error) {
                Notiflix.Notify.Failure( 'Order Status Update Failed' );
            }

        }
    });
});
// Sales Product Model Details Data View
jQuery(document).on("click","#viewProductSalesDetails",function(e){
  e.preventDefault();
  $('#itemList').html("");
  var modelNumber = jQuery(this).data('id');
  var url = "productSalesReportDetails"+"/"+modelNumber;
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    beforeSend: function() {
        Notiflix.Loading.Arrows('Data Processing');
        Notiflix.Loading.Remove(300);
    },
    success:function(response){
        console.log(response);
        Notiflix.Loading.Remove(300);
        if(response == "error"){
            Notiflix.Notify.Failure( 'Data Not Found' );
            setTimeout(function(){// wait for 5 secs(2)
                window.location.reload(); // then reload the page.(3)
                $(".btnCloseModal").click();
            }, 1000);
        }

        if(response) {
            jQuery('#itemList').append(response.itemList);
        }
    }
  });
});
// Seller Sales Product Model Details Data View
jQuery(document).on("click","#SellerProductSalesDetails",function(e){
  e.preventDefault();
  $('#itemList').html("");
  var modelSellerId = jQuery(this).data('id');
  var url = "sellerProductSalesReport"+"/"+modelSellerId;
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    beforeSend: function() {
        Notiflix.Loading.Arrows('Data Processing');
        Notiflix.Loading.Remove(300);
    },
    success:function(response){
        console.log(response);
        Notiflix.Loading.Remove(300);
        if(response == "error"){
            Notiflix.Notify.Failure( 'Data Not Found' );
            setTimeout(function(){// wait for 5 secs(2)
                window.location.reload(); // then reload the page.(3)
                $(".btnCloseModal").click();
            }, 1000);
        }

        if(response) {
            jQuery('#itemList').append(response.itemList);
        }
    }
  });
});
//View Dealer Details
jQuery(document).on("click","#viewDealerDetails",function(e){
  e.preventDefault();
  $('#dealerResultInfo').html('');
  var DealerId = jQuery(this).data('id');
  var url = "dealer.show"+"/"+DealerId;
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    beforeSend: function() {
        Notiflix.Loading.Arrows('Data Processing');
        Notiflix.Loading.Remove(300);
    },
    success:function(response) {
        console.log(response);
        if(response) {
            console.log(response);
            Notiflix.Loading.Remove();
            if(response.status == 'info') {
                //Notiflix.Notify.Success('Dealer Information Available');
                jQuery('#dealerResultInfo').append(response.data);
                jQuery('#dealerResultInfo').show();
            }
            if(response.status == 'error') {
                Notiflix.Loading.Remove(300);
                Notiflix.Notify.Warning('Dealer Not Found');
                jQuery('#dealerResultInfo').hide();
            }  
        } else {
            Notiflix.Notify.Failure( 'Dealer Not Found' );
            Notiflix.Loading.Remove(300);
            //jQuery('#imeResultInfo').hide();
        }

        if(response == 'empty' || response == 'error') {
            Notiflix.Notify.Info( 'Dealer Not Found! Please Try Another Dealer' );
        }
        
    }
  });
});
</script>

<script type="text/javascript">
// CSRF Token
var CSRF_TOKEN = jQuery('meta[name="csrf-token"]').attr('content');
jQuery(document).ready(function() {
    jQuery( "#bp_search" ).autocomplete({
        source: function( request, response ) {
            // Fetch data
            jQuery.ajax({
                url: "{{url('bp_search')}}",
                method:"GET",
                dataType: "json",
                data: {
                   _token: CSRF_TOKEN,
                   search: request.term
                },
                success: function(data){
                  console.log(data);
                  response(data);
                }
            });
        },
        select: function (event, ui) {
            // Set selection
            $('#bp_search').val(ui.item.label); // display the selected text
            $('#bp_id').val(ui.item.value); // save selected id to input
            return false;
        }
    });

    jQuery( "#retailer_search" ).autocomplete({
        source: function( request, response ) {
            // Fetch data
            jQuery.ajax({
                url: "{{url('retailer_search')}}",
                method:"GET",
                dataType: "json",
                data: {
                   _token: CSRF_TOKEN,
                   search: request.term
                },
                success: function(data){
                  console.log(data);
                  response(data);
                }
            });
        },
        select: function (event, ui) {
            // Set selection
            $('#retailer_search').val(ui.item.label); // display the selected text
            $('#retailer_id').val(ui.item.value); // save selected id to input
            $('#retailer_name').val(ui.item.retailer_name); // save selected id to input
            return false;
        }
    });
});
</script>

<script type="text/javascript">
var CSRF_TOKEN = jQuery('meta[name="csrf-token"]').attr('content');
jQuery(document).on('change','#zone',function(){
    var zoneIds = $(this).val();
    //alert(zoneIds);
    jQuery.ajax({
        url: "{{url('searchRetailer')}}",
        method:"GET",
        dataType: "json",
        data: {
            _token: CSRF_TOKEN,
            search: zoneIds
        },
        success: function(data){
            console.log(data);
            jQuery('#retailerList').append(data);
        }
    });
});
// Pre Booking Order  Model Details Data View
jQuery(document).on("click","#viewOrderSalesDetails",function(e){
  e.preventDefault();
  $('#orderList').html("");
  var getModel = jQuery(this).data('id');
  var url = "preOrderReportDetails"+"/"+getModel;
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    beforeSend: function() {
        Notiflix.Loading.Arrows('Data Processing');
        Notiflix.Loading.Remove(300);
    },
    success:function(response){
        console.log(response);
        Notiflix.Loading.Remove(300);
        if(response == "error"){
            Notiflix.Notify.Failure( 'Data Not Found' );
            setTimeout(function(){// wait for 5 secs(2)
                window.location.reload(); // then reload the page.(3)
                $(".btnCloseModal").click();
            }, 1000);
        }

        if(response) {
            jQuery('#orderList').append(response.itemList);
        }
    }
  });
});
//Employee On Change Function Start
jQuery(document).on('change','.empId',function(){
    var empId = jQuery(this).val();
    var url   = "getEmployeeInfo"+"/"+empId
    jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    success:function(response) {
        console.log(response);
        if(response.name) {
            jQuery('.uname').val(response.name);
        }
        if(response.email) {
            jQuery('.uemail').val(response.email);
        }
    }
  });
});
//Get Current Stock By Dealer,Retailer & Employee
jQuery(document).on('change','#clientType',function(){
    jQuery('.searchId').html("");
    var getType = $(this).val();
    if(getType == 'retailer') {
        $html = '<label>Retailer Phone</label>&nbsp;<span class="required">*</span><input type="text" name="search_id" class="form-control" placeholder="Ex: 01796452391" min="11" required>';

        jQuery('.searchId').append($html);
    }
    else if(getType == 'dealer') {
        $html = '<label>Dealer Code / Phone Number</label>&nbsp;<span class="required">*</span><input type="text" name="search_id" class="form-control" placeholder="Ex: 58396 / 01676053537" min="3" required>';

        jQuery('.searchId').append($html);
    }
    if(getType == 'emp') {
        $html = '<label>Employee ID</label>&nbsp;<span class="required">*</span><input type="text" name="search_id" class="form-control" placeholder="Ex: 14909" min="3" required>';

        jQuery('.searchId').append($html);
    }
});

function getStockType($type=null) {
    if($type == 1) {
        jQuery('.resultType').val($type);
    } else {
        jQuery('.resultType').val(2);
    }
    setTimeout(function() {
        $('#getStockDownload').fadeIn('fast');
    }, 100000); // <-- time in milliseconds
}
jQuery("form#stockSearch").on('submit', function(event){
    jQuery.ajax({
        url:"get_stock",
        method:"POST",
        data:new FormData(this),
        dataType:'JSON',
        contentType: false,
        cache: false,
        processData: false,
        error:function(error){
          Notiflix.Loading.Arrows('Request Sending...');
        }
    });
});
// Listen for click on toggle checkbox
jQuery('#select-all').click(function(event) {   
    if(this.checked) {
        // Iterate each checkbox
        jQuery(':checkbox').each(function() {
            this.checked = true;                        
        });
    } else {
        jQuery(':checkbox').each(function() {
            this.checked = false;                       
        });
    }
});
jQuery('.checkParentMenuId').click(function(event) {
    var menuParentId  = jQuery(this).data('id');  
    if(this.checked) {
        // Iterate each checkbox
        jQuery('.childParentId_'+menuParentId).each(function() {
            this.checked = true;                        
        });
    } else {
        jQuery('.childParentId_'+menuParentId).each(function() {
            this.checked = false;                       
        });
    }
});
//BP or Retail Category On Change Function Start
jQuery(document).on("change","#category_id",function(e){
  e.preventDefault();
  var catId = $(this).val();
  var url   = "bpromoter.focus_model_to_bp_by_cat"+"/"+catId
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    success:function(response) {
        console.log(response);
        $('.catId').val(response.catId);
        window.location.reload();
    },
    error:function(error) {
      //window.location.reload();
    }
  });
});
jQuery(document).on("click","#getImeiCheckModal",function(e) {
    jQuery('#imeResultInfo').html("");
    jQuery('#search_ime').val("");
});
//Model Search Combo Box
jQuery( "#model_search" ).autocomplete({
    source: function( request, response ) {
        // Fetch data
        jQuery.ajax({
            url: "{{url('product_model_search')}}",
            method:"GET",
            dataType: "json",
            data: {
               _token: CSRF_TOKEN,
               search: request.term
            },
            success: function(data){
              console.log(data);
              response(data);
            }
        });
    },
    select: function (event, ui) {
        // Set selection
        $('#model_search').val(ui.item.label); // display the selected text
        $('#product_id').val(ui.item.value); // save selected id to input
        $('#model_name').val(ui.item.model_name); // save selected id to input
        return false;
    }
});
//Dealer Search Combo Box
jQuery( "#dealer_search_list" ).autocomplete({
    source: function( request, response ) {
        // Fetch data
        jQuery.ajax({
            url: "{{url('dealerSearch')}}",
            method:"GET",
            dataType: "json",
            data: {
               _token: CSRF_TOKEN,
               search: request.term
            },
            success: function(data){
              console.log(data);
              response(data);
            }
        });
    },
    select: function (event, ui) {
        // Set selection
        $('#dealer_search_list').val(ui.item.label); // display the selected text
        $('#dealer_code').val(ui.item.value); // save selected id to input
        //$('#dealer_name').val(ui.item.model_name); // save selected id to input
        return false;
    }
});
$('.photoIdModal').click(function(event) {   
    event.preventDefault();
    var getSrc = jQuery(this).data('id');
    var photoUrl = APP_URL+'/public/upload/client/'+getSrc;
    $('#photoId').attr("src", photoUrl ); 
});
$('.bannerphotoIdModal').click(function(event) {   
    event.preventDefault();
    var getSrc = jQuery(this).data('id');
    //alert(getSrc);exit();
    var photoUrl = APP_URL+'/public/upload/banner/'+getSrc;
    $('#photoId').attr("src", photoUrl ); 
});
$('.attendancePhotoIdModal').click(function(event) {   
    event.preventDefault();
    var getSrc = jQuery(this).data('id');
    //alert(getSrc);exit();
    var photoUrl = APP_URL+'/public/upload/bpattendance/'+getSrc;
    $('#photoId').attr("src", photoUrl ); 
});

//GET Excel Download
jQuery(document).on("click","#getStockDownload",function(e){
    e.preventDefault();
    var url = "getStockExcelDownload";
    $('#setDownloadUrl').attr("");
    jQuery.ajax({
        url:url,
        type:"GET",
        dataType:"JSON",
        beforeSend: function() {
            Notiflix.Loading.Arrows('Data Processing');
        },
        success:function(response) {
            console.log(response);
            Notiflix.Loading.Remove(300);
            if(response) {
                window.location.href = APP_URL+'/public/upload/stock_excel_download/'+response;
            }
            if(response.fail) {
                if(response.errors.name) {
                    Notiflix.Notify.Failure('Data Processing Failed');
                    setTimeout(function(){// wait for 5 secs(2)
                        window.location.reload(); // then reload the page.(3)
                        $(".btnCloseModal").click();
                    }, 2000);
                }
            }
        },
        error:function(error) {
            Notiflix.Notify.Failure('Data Processing Failed');
            setTimeout(function(){// wait for 5 secs(2)
                window.location.reload(); // then reload the page.(3)
                $(".btnCloseModal").click();
            }, 2000);
        }
    })
});
jQuery(document).on("click",".getStockDownload",function(e){
    e.preventDefault();
    $('.excelDownloadTitle').fadeOut();
    var searchId    = $('#searchId').val();
    var clientType  = $('#clientType').val();
    var searchModel = JSON.stringify($('#product_model').val());
    
    var url = "getStockExcelDownload"+'/'+clientType+'/'+searchId+'/'+searchModel;
    
    $('#setDownloadUrl').attr("");
    jQuery.ajax({
        url:url,
        type:"GET",
        dataType:"JSON",
        success:function(response) {
            console.log(response);
            /*if(response) {
                window.location.href = APP_URL+'/public/upload/stock_excel_download/'+response;
            }*/

            if(response)
            {
                var downloadUrl = APP_URL+'/public/upload/stock_excel_download/'+response;
                $('.excelDownloadTitle').fadeIn();
                $("#setStockDownloadUrl").attr("href", downloadUrl);

                window.setTimeout(function () { 
                    $('.excelDownloadTitle').fadeOut('fast');
                    unlink(downloadUrl);
                }, 600000);
            }
        },
        error:function(error) {
            Notiflix.Notify.Failure('Data Processing Failed');
            setTimeout(function(){// wait for 5 secs(2)
                window.location.reload(); // then reload the page.(3)
                $(".btnCloseModal").click();
            }, 2000);
        }
    })
});
//Button Auto Hide/Disable
/*
jQuery(document).ready(function() {
 window.setTimeout(function () { 
        $("#getStockDownload").prop('disabled', false);
    }, 600000);
    setTimeout(function() {
        $('#getStockDownload').fadeOut('fast');
    }, 600000); // <-- time in milliseconds
});
*/
jQuery('#get_retailer_search').keyup(function() {
  if ($(this).val().length == 0) {
    $('#set_retailer_search').hide();
  } else {
    $('#set_retailer_search').show();
  }
}).keyup();
jQuery("#get_retailer_search").autocomplete({
    source: function( request, response ) {
        // Fetch data
        jQuery.ajax({
            url: "{{url('get_retailer_search')}}",
            method:"GET",
            dataType: "json",
            data: {
               _token: CSRF_TOKEN,
               search: request.term
            },
            success: function(data){
              console.log(data);
              if(data !=null)
              {
                $('#set_retailer_search').fadeIn('fast');
                $('#set_retailer_search').append(data);
              }
              else
              {
                $('#set_retailer_search').fadeOut('fast');
              }
              
            }
        });
    },
    /*select: function (event, ui) {
        // Set selection
        $('#retailer_search').val(ui.item.label); // display the selected text
        $('#retailer_id').val(ui.item.value); // save selected id to input
        $('#retailer_name').val(ui.item.retailer_name); // save selected id to input
        return false;
    }*/
});
jQuery(document).on("change",".product_model",function(e){
  e.preventDefault();
  var getProductId  = $(this).val();
  var url           = "product.get_model_price"+"/"+getProductId;
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    success:function(response) {
        console.log(response);
        if(response)
        {
            $('#model_price').val(response).prop('disabled',true);
            $('#update_model_price').val(response).prop('disabled',true);
            $('#setPrice').val(response);
            $('#updateSetPrice').val(response);
        }
        else
        {
            $('#model_price').val(0).prop('disabled',true);
            $('#update_model_price').val(0).prop('disabled',true);
            $('#setPrice').val(0);
            $('#updateSetPrice').val(0);
        }

        if(response.error)
        {
            Notiflix.Notify.Failure('Data Processing Failed');
        }
    },
    error:function(error) {
      //window.location.reload();
    }
  });
});
</script>
@yield('js')