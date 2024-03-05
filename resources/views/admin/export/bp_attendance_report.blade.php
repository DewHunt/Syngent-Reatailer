<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>BP Sales Report</title>
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
            <h1>BP Attendance Report</h1>
        </div>
        <table id="reports">
			<thead>
				<tr>
					<th>Sl.</th>
					<th>Selfi Photo</th>
					<th>BP Name</th>
					<th>In Status</th>
					<th>Out Status</th>
					<th>Location</th>
					<th>Attendance Date</th>
					<th>In Time & Location</th>
					<th>Out Time & Location</th>
				</tr>
			</thead>
			<tbody>
				@if(isset($bpAttendanceArray))
				@foreach($bpAttendanceArray as $row)
				<tr>
					<td>{{ ++$loop->index }}.</td>
					@if(isset($row->get_oldest_attendances))
					<td>
						<a href="javascript:void(0)">
							<img src="{{ asset('public/upload/bpattendance/'.$row->get_oldest_attendances['selfi_pic']) }}" alt="photo" width="50" height="50"/>
						</a>
					</td>
					<td>{{ ($row->bp_name) ? $row->bp_name : '' }}</td>
					@if($row->id > 0)
						@php
						$status = "Absent";
						$checkStatus = getBpLeaveStatus($row->id);
						if($checkStatus == 'Approved'){
							$status =  "Leave";
						}
						@endphp
					@endif
					@if(isset($row->get_latest_attendances))
					<td>
						<span class="badge badge-primary badge-sm"> 
							{{ ($row->get_latest_attendances['in_status']) ? $row->get_latest_attendances['in_status']:$status  }}
						</span>
					</td>
					@else
					<td>{{ '--' }}</td>
					@endif
					<td>
						{{ ($row->get_oldest_attendances['out_status']) ? $row->get_oldest_attendances['out_status']:'' }}
					</td>
					<td>
						{{ ($row->get_latest_attendances['location']) ? $row->get_latest_attendances['location']:'' }}
					</td>

					<td>
						{{ ($row->get_latest_attendances['date']) ? $row->get_latest_attendances['date']:date('Y-m-d') }}
					</td>
					@if(isset($row->get_latest_attendances))
					<td>
						{{ ($row->get_latest_attendances['date']) ? $row->get_latest_attendances['date']:''  }}<br/>
						{{ ($row->get_latest_attendances['location']) ? $row->get_latest_attendances['location']:'' }}
					</td>
					@endif

					<td>
						{{ ($row->get_oldest_attendances['date']) ? $row->get_oldest_attendances['date']:''  }}<br/>
						{{ ($row->get_oldest_attendances['location']) ? $row->get_oldest_attendances['location']:'' }}
					</td>
					@elseif(isset($row->get_latest_attendances))
					<td>
						<a href="javascript:void(0)">
							<img src="{{ asset('public/upload/bpattendance/'.$row->get_latest_attendances['selfi_pic']) }}" alt="photo" width="50" height="50"/>
						</a>
					</td>
					<td>{{ ($row->bp_name) ? $row->bp_name : '' }}</td>

					@if($row->get_latest_attendances['bp_id'] > 0)
						@php
						$status = "Absent";
						$checkStatus = getBpLeaveStatus($row->get_latest_attendances['bp_id']);
						if($checkStatus == 'Approved'){
							$status =  "Leave";
						}
						@endphp
					@endif
					<td>
						<span class="badge badge-primary badge-sm"> 
							{{ ($row->get_latest_attendances['in_status']) ? $row->get_latest_attendances['in_status']:$status  }}
						</span>
					</td>
					<td>{{ ($row->get_latest_attendances['out_status']) ? $row->get_latest_attendances['out_status']:'' }}</td>
					<td>{{ ($row->get_latest_attendances['location']) ? $row->get_latest_attendances['location']:'' }}</td>
					<td>{{ ($row->get_latest_attendances['date']) ? $row->get_latest_attendances['date']:date('Y-m-d') }}</td>
					<td>
						{{ ($row->get_latest_attendances['date']) ? $row->get_latest_attendances['date']:''  }}<br/>
						{{ ($row->get_latest_attendances['location']) ? $row->get_latest_attendances['location']:'' }}
					</td>
					<td>
						{{ ($row->get_latest_attendances['date']) ? $row->get_latest_attendances['date']:''  }}<br/>
						{{ ($row->get_latest_attendances['location']) ? $row->get_latest_attendances['location']:''  }}
					</td>
					@else
					<td>
						<img src="{{ asset('public/upload/client/no-image.png') }}" alt="photo" width="50" height="50"/>
					</td>
					<td>{{ ($row->bp_name) ? $row->bp_name : '' }}</td>

					@if($row->id > 0)
						@php
						$status = "Absent";
						$checkStatus = getBpLeaveStatus($row->id);
						if($checkStatus == 'Approved'){
							$status =  "Leave";
						}
						@endphp
					@endif

					<td>
						<span class="badge badge-primary badge-sm"> 
							{{ ($status) ? $status:'--'  }}
						</span>
					</td>
					<td>{{ '--' }}</td>
					<td>{{ '' }}</td>
					<td>{{ date('Y-m-d') }}</td>
					<td>{{ '--' }}</td>
					<td>{{ '--' }}</td>
					@endif
				</tr>
				@endforeach
				@endif
				</tbody>
		</table>
	</body>
</html>