@if(isset($notification_list))
    @foreach($notification_list as $row)
    <tr>
        <td>{{ ++$loop->index }}.</td>
        <td>{{ $row->title }}</td>
        <td>{{ $row->message }}</td>
        <td class="text-center">
            <button type="button" data-id="{{ $row->id }}" id="editPushNotificationInfo" class="btn btn-primary btn-sm btnCommonWidth" data-toggle="modal" data-target="#editPushNotificationModal">Edit</button>
            <button type="button" data-id="{{ $row->id }}" id="getPushNotificationInfo" class="btn btn-info btn-sm btnCommonWidth" data-toggle="modal" data-target="#getPushNotificationModal">Send</button>
        </td>
    </tr>
    @endforeach
	<tr><td colspan="4" align="center">{!! $notification_list->links() !!}</td></tr>
@endif

                    