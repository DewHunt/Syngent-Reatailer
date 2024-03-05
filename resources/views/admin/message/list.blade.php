@extends('admin.master.master')
@section('content')
<style>
    .cp {
        padding:5px
    }
    .csearch {
        width:205px;
    }           
    /* Portrait and Landscape */
    @media only screen 
    and (min-device-width: 320px) 
    and (max-device-width: 568px)
    and (-webkit-min-device-pixel-ratio: 2) {
        .cp {
            padding:5px
        }
        .csearch {
            width:300px;
        }
    }
    @media only screen 
    and (min-device-width: 375px) 
    and (max-device-width: 812px) 
    and (-webkit-min-device-pixel-ratio: 3){
        .cp {
            padding:5px
        }
        .csearch {
            width:300px;
        }
    }
    @media (min-width: 768px) and (max-width: 1024px) {
        .cp {
            padding:5px
        }
        .csearch {
            width:300px;
        }
    }
</style>
<div class="col-md-12 cp">
    <div class="row" style="margin-right:-20px">
        <div class="col-md-6"><h4 class="c-grey-900 mB-20">Message List</h4></div>
        <div class="col-md-6">
            <div class="form-group top-margin">
                <input type="text" name="serach" id="serach" class="form-control pull-right csearch"/>
            </div>
        </div>
    </div>
</div>
<div id="tag_container" class="table-responsive">
    <table class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="sorting" data-sorting_type="desc" data-column_name="id" style="cursor: pointer;">Sl.</th>
                <th class="sorting" data-sorting_type="desc" data-column_name="reply_user_name" style="cursor: pointer;">Sender Name</th>
                <th class="sorting" data-sorting_type="desc" data-column_name="message" style="cursor: pointer;">Message</th>
                <th class="sorting" data-sorting_type="desc" data-column_name="date_time" style="cursor: pointer;">Date & Time</th>
                <th class="sorting" style="cursor: pointer;">Reply Message</th>
            </tr>
        </thead>
        <tbody>
            @include('admin.message.result_data')
        </tbody>
    </table>
    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="desc" />
</div>

<style type="text/css">
    .text-small {
        font-size: 0.9rem;
    }

    .messages-box,
    .chat-box {
        height: 350px;
        overflow-y: scroll;
    }

    .rounded-lg {
        border-radius: 0.5rem;
    }

    input::placeholder {
        font-size: 0.9rem;
        color: #999;
    }
    /* Portrait and Landscape */
    @media only screen 
    and (min-device-width: 320px) 
    and (max-device-width: 568px)
    and (-webkit-min-device-pixel-ratio: 2) {
        .table-responsive .btn {
            padding: 1rem !important;
            font-size: 1.5rem !important;
        }
        .cvbtn {
            width: 210px !important;
            height: 65px !important;
        }
    }
    /* Portrait and Landscape */
    @media only screen 
    and (min-device-width: 375px) 
    and (max-device-width: 812px) 
    and (-webkit-min-device-pixel-ratio: 3){
        .table-responsive .btn {
            padding: 1rem !important;
            font-size: 1.5rem !important;
        }
        .cvbtn {
            width: 210px !important;
            height: 65px !important;
        }
    }
    @media (min-width: 768px) and (max-width: 1024px) {
        .table-responsive .btn {
            padding: 1rem !important;
            font-size: 1.5rem !important;
        }
        .cvbtn {
            width: 210px !important;
            height: 65px !important;
        }
    }
</style>


<!--View Product Modal Start -->
<div class="modal fade" id="viewMessageDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Message Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <!-- Chat Box-->
                            <div class="col-12 px-0">
                                <div class="chat-box bg-white">
                                    <div id="sendMessage"></div>
                                    <div id="replyMessage"></div>
                                </div>
                            </div>
                            <form class="form-horizontal" method="POST" action="" id="ReplyMessage">
                                @csrf
                                <div class="form-row" style="margin-top:10px">
                                    <div class="form-group col-md-10">
                                        <input type="text" class="form-control" id="reply_message" name="reply_message">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <input type="hidden" name="reply_id" id="replyId" readonly="">
                                        <input type="hidden" name="message_id" id="messageId" readonly="">
                                        <button type="submit" class="btn btn-primary btn-block message-table">Send</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button>  
            </div>
        </div>
    </div>
</div>
<!--View Product Modal End -->


@section('page-scripts')
<script type="text/javascript">
// Message Details Data View
jQuery(document).on("click","#MessageDetailsView",function(e){
  e.preventDefault();
  $('#reply_message').html("");
  $('#sendMessage').html('');
  $('#replyMessage').html('');
  var messageId = $('#MsgId').val();
  var replyId   = jQuery(this).data('id');
  var url = "message.details"+"/"+replyId+"/"+messageId;
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    beforeSend: function() {
        Notiflix.Loading.Arrows('Data Processing');
        Notiflix.Loading.Remove(900);
    },
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

        if(response.sendMessage) {
            jQuery('#messageId').val(response.messageId);
            jQuery('#replyId').val(response.replyId);
            //jQuery('#sendMessage').append(response.sendMessage);
            jQuery('#replyMessage').append(response.replyMessage);
            $('#reply_message').html("");
        }
    }
  });
});
// Message Reply Data View
jQuery('#ReplyMessage').submit(function(e){
  e.preventDefault();
  $('#sendMessage').html('');
  $('#replyMessage').html('');
  jQuery.ajax({
    url:"message.reply",
    method:"POST",
    data:new FormData(this),
    dataType:'JSON',
    contentType: false,
    cache: false,
    processData: false,

    success:function(response) {
        Notiflix.Loading.Arrows('Reply Message Processing');
        console.log(response);
        Notiflix.Loading.Remove(300);
        if(response.error) {
            Notiflix.Notify.Failure('Message Not Reply');
            setTimeout(function(){// wait for 5 secs(2)
                window.location.reload(); // then reload the page.(3)
                $(".btnCloseModal").click();
            }, 1000);
        }

        if(response.sendMessage) {
            if(response.status == 'success')
            {
                Notiflix.Notify.Success('Message Reply Successfully');
                jQuery('#messageId').val(response.messageId);
                jQuery('#replyId').val(response.replyId);
                //jQuery('#sendMessage').append(response.sendMessage);
                jQuery('#replyMessage').append(response.replyMessage);
                $('#reply_message').val("");
            }
            
        }
    },
    error:function(error){
      Notiflix.Notify.Failure('Message Reply Failed.Please Try Again');
    }
  });
});
</script>

<!--Pagination Script Start-->
<script>
function clear_icon() {
    $('#id_icon').html('');
    $('#post_title_icon').html('');
}

function fetch_data(page, sort_type, sort_by, query) {
    $.ajax({
        url:"?page="+page+"&sortby="+sort_by+"&sorttype="+sort_type+"&query="+query,
        type:"get",
        success:function(data) {
            console.log(data);
            $('tbody').html('');
            $('tbody').html(data);
            $(this).data('toggle-on', true);
            jQuery('.toggle').each(function() {
                $(this).toggles({
                    on: $(this).data('toggle-on')
                });
            });
            if(!empty(data.json_data_for_excel_and_pdf))
            {
                $('#export_data').val(data.json_data_for_excel_and_pdf);
            }
            jQuery('input[type=checkbox][data-toggle^=toggle]').bootstrapToggle();
            var toggleJs  = APP_URL+"/public/admin/js/custom-js/toggle-information.js";
            jQuery.getScript(toggleJs);
        }
    })
}

jQuery(document).ready(function() {
    var searhText = document.getElementById('serach');
    searhText.onkeydown = function() {
        var key = event.keyCode || event.charCode;
        if( key == 8 ) {
            var getSearchVal = $('#serach').val();
            var length = getSearchVal.length;
            if(length <= 1) {
                var query       = $('#serach').val();
                var column_name = $('#hidden_column_name').val();
                var sort_type   = $('#hidden_sort_type').val();
                var page        = $('#hidden_page').val();
                fetch_data(page, sort_type, column_name, query);
            }
        }
    };

    jQuery(document).on('keyup', '#serach', function() {
        var getSearchVal = $('#serach').val();
        var length = getSearchVal.length;
        if(length >=2) {
            var query       = $('#serach').val();
            var column_name = $('#hidden_column_name').val();
            var sort_type   = $('#hidden_sort_type').val();
            var page        = $('#hidden_page').val();
            fetch_data(page, sort_type, column_name, query);
        }
    });

    jQuery(document).on('click', '.sorting', function(){
        var column_name     = $(this).data('column_name');
        var order_type      = $(this).data('sorting_type');
        var reverse_order   = '';
        if(order_type == 'asc') {
            $(this).data('sorting_type', 'desc');
            reverse_order = 'desc';
            clear_icon();
            $('#'+column_name+'_icon').html('<span class="glyphicon glyphicon-triangle-bottom"></span>');
        }
        if(order_type == 'desc') {
            $(this).data('sorting_type', 'asc');
            reverse_order = 'asc';
            clear_icon
            $('#'+column_name+'_icon').html('<span class="glyphicon glyphicon-triangle-top"></span>');
        }
        $('#hidden_column_name').val(column_name);
        $('#hidden_sort_type').val(reverse_order);
        var page    = $('#hidden_page').val();
        var query   = $('#serach').val();
        fetch_data(page, reverse_order, column_name, query);
    });

    
    jQuery(document).on('click', '.pagination a', function(event){
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        $('#hidden_page').val(page);
        var column_name = $('#hidden_column_name').val();
        var sort_type   = $('#hidden_sort_type').val();
        var query       = $('#serach').val();
        $('li').removeClass('active');
        $(this).parent().addClass('active');
        fetch_data(page, sort_type, column_name, query);
    });
    
    // jQuery('.btnCloseModal').trigger('click');
    // jQuery('.btnCloseModal').mousedown();
    // jQuery('.close').click();
});
</script>
@endsection


@endsection