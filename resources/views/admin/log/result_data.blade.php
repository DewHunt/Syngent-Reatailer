@if(isset($loginLogList))
    @foreach($loginLogList as $row)
    <tr>
        <td>{{ ++$loop->index }}.</td>
        <td>{{ $row->name }}</td>
        <td>{{ $row->type }}</td>
         <td>{{ $row->user_agent }}</td>
        <td>{{ $row->ip_address }}</td>
        <td>
            <span class="c-green-500">
                {{ date('d M Y h:i:s a',strtotime($row->created_at)) }}
            </span>
        </td>
    </tr>
    @endforeach
	<tr><td colspan="6" align="center">{!! $loginLogList->links() !!}</td></tr>
@endif
    


                    