@if(isset($MessageList))
    @foreach($MessageList as $row)
    <tr>
        <td>{{ ++$loop->index }}.</td>
        <td>
            @php $getResponse = getNameFirstMessageSender($row->reply_for) @endphp
            {{ $getResponse->reply_user_name }}
            <br/>{{ $getResponse->phone }} - {{ $getResponse->zone }}
        </td>
        <!--{{ $row->reply_user_name }}-->
        <td>{{ $row->message }}</td>
        <td>{{ $row->date_time }}</td>
        <td style="text-align: center;">
            <button type="button" data-id="{{ $row->reply_for }}" id="MessageDetailsView" class="btn cur-p btn-info btn-xs cvbtn" data-toggle="modal" data-target="#viewMessageDetailsModal" style="padding: 2px 6px;">
                 Reply Now
            </button>
            <input type="hidden" id="MsgId" value="{{ $row->id }}"/>
        </td>
    </tr>
    @endforeach
	<tr><td colspan="5" align="center">{!! $MessageList->links() !!}</td></tr>
@endif
    


                    