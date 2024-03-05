<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>User Login Activities Reports</title>
	<style type="text/css">
		.center{
			text-align:center;
		}
		table, td, th {
			border: 1px solid black;
			font-size:10px;
			padding:0px 6px;
		}
		.text-right{
			text-align:right
		}
		.text-left{
			text-align:left
		}

		table {
			width: 100%;
			border-collapse: collapse;
		}
	</style>
</head>
<body>
	<div class="center">
		<h1>User Login Activities Reports</h1>
	</div>
	<table id="reports">
		<thead>
			<tr>
				<th>Sl.</th>
				<th>Name</th>
				<th>Type</th>
				<th>User Agent</th>
				<th>IP Address</th>
				<th>Login Time</th>
			</tr>
		</thead>
		<tbody>
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
		@endif
		</tbody>
	</table>
</body>
</html>