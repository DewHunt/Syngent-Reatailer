@if(isset($bpAttendanceArray))
    @foreach($bpAttendanceArray as $row)

    @if(isset($row->id) && $row->id > 0)
        @php
        $status = "Absent";
        $checkStatus = getBpLeaveStatus($row->id,$getDate);
        if($checkStatus == 'Approved'){
            $status =  "Leave";
        }
        @endphp
    @endif

    @if($getOrderBy == "all")
        <tr>
            <td>{{ ++$loop->index }}.</td>
            @if(isset($row->getOldestAttendances))
                <td>
                    <a href="javascript:void(0)" class="attendancePhotoIdModal" data-id="{{ $row->getOldestAttendances['selfi_pic'] }}" data-toggle="modal" data-target="#viewPhotoModal">
                        <img src="{{ asset('public/upload/bpattendance/'.$row->getOldestAttendances['selfi_pic']) }}" alt="photo" width="50" height="50"/>
                    </a>
                </td>
                <td>{{ ($row->bp_name) ? $row->bp_name : '' }}</td>
                <td>{{ ($row->bp_phone) ? $row->bp_phone : '' }}</td>
				<td>{{ ($row->distributor_name) ? $row->distributor_name : '' }}</td>
				<td>{{ ($row->distributor_code) ? $row->distributor_code : '' }}</td>
                @if(isset($row->getLatestAttendances))
                <td>
                    <span class="badge badge-primary badge-sm"> 
                        {{ ($row->getLatestAttendances['in_status']) ? $row->getLatestAttendances['in_status']:$status  }}
                    </span>
                </td>
                @else
                <td>{{ '--' }}</td>
                @endif
                <td>
                    {{ ($row->getOldestAttendances['out_status']) ? $row->getOldestAttendances['out_status']:'' }}
                </td>
                <td>
                    {{ ($row->getLatestAttendances['location']) ? $row->getLatestAttendances['location']:'' }}
                </td>
                <td>
                    {{ ($row->getLatestAttendances['date']) ? $row->getLatestAttendances['date']:date('Y-m-d') }}
                </td>
                @if(isset($row->getLatestAttendances))
                <td>
                    {{ ($row->getLatestAttendances['date']) ? $row->getLatestAttendances['date']:''  }}<br/>
                    {{ ($row->getLatestAttendances['location']) ? $row->getLatestAttendances['location']:'' }}
                </td>
                @endif
                <td>
                    {{ ($row->getOldestAttendances['date']) ? $row->getOldestAttendances['date']:''  }}<br/>
                    {{ ($row->getOldestAttendances['location']) ? $row->getOldestAttendances['location']:'' }}
                </td>
                <td style="text-align: center;">
                    <a href="javascript:void(0)">
                        <button type="button" data-id="{{ $row->id }}" id="bpAttendanceDetails" class="btn cur-p btn-info btn-xs eyeViewbtn" data-toggle="modal" data-target="#viewAttendanceDetailsModal"><i class="fa fa-eye" aria-hidden="true"></i></button>
                    </a>
                </td>
            @elseif(isset($row->getLatestAttendances))
            
                <td>
                    <a href="javascript:void(0)" class="attendancePhotoIdModal" data-id="{{ $row->getLatestAttendances['selfi_pic'] }}" data-toggle="modal" data-target="#viewPhotoModal">
                        <img src="{{ asset('public/upload/bpattendance/'.$row->getLatestAttendances['selfi_pic']) }}" alt="photo" width="50" height="50"/>
                    </a>
                </td>
                <td>{{ ($row->bp_name) ? $row->bp_name : '' }}</td>
                <td>{{ ($row->bp_phone) ? $row->bp_phone : '' }}</td>
				<td>{{ ($row->distributor_name) ? $row->distributor_name : '' }}</td>
				<td>{{ ($row->distributor_code) ? $row->distributor_code : '' }}</td>
                <td>
                    <span class="badge badge-primary badge-sm"> 
                        {{ ($row->getLatestAttendances['in_status']) ? $row->getLatestAttendances['in_status']:$status  }}
                    </span>
                </td>
                <td>{{ ($row->getLatestAttendances['out_status']) ? $row->getLatestAttendances['out_status']:'' }}</td>
                <td>{{ ($row->getLatestAttendances['location']) ? $row->getLatestAttendances['location']:'' }}</td>
                <td>{{ ($row->getLatestAttendances['date']) ? $row->getLatestAttendances['date']:date('Y-m-d') }}</td>
                <td>
                    {{ ($row->getLatestAttendances['date']) ? $row->getLatestAttendances['date']:''  }}<br/>
                    {{ ($row->getLatestAttendances['location']) ? $row->getLatestAttendances['location']:'' }}
                </td>
                <td>
                    {{ ($row->getLatestAttendances['date']) ? $row->getLatestAttendances['date']:''  }}<br/>
                    {{ ($row->getLatestAttendances['location']) ? $row->getLatestAttendances['location']:''  }}
                </td>
                <td style="text-align: center;">
                    <a href="javascript:void(0)">
                        <button type="button" data-id="{{ $row->getLatestAttendances['bp_id'] }}" id="bpAttendanceDetails" class="btn cur-p btn-info btn-xs eyeViewbtn" data-toggle="modal" data-target="#viewAttendanceDetailsModal"><i class="fa fa-eye" aria-hidden="true"></i></button>
                    </a>
                </td>
            @else
                <td>
                    <img src="{{ asset('public/upload/client/no-image.png') }}" alt="photo" width="50" height="50"/>
                </td>
                <td>{{ ($row->bp_name) ? $row->bp_name : '' }}</td>
                <td>{{ ($row->bp_phone) ? $row->bp_phone : '' }}</td>
				<td>{{ ($row->distributor_name) ? $row->distributor_name : '' }}</td>
				<td>{{ ($row->distributor_code) ? $row->distributor_code : '' }}</td>
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
                <td style="text-align: center;"></td>
            @endif
        </tr>
    @elseif($getOrderBy == "present")
        @if(isset($row->getLatestAttendances) && $row->getLatestAttendances['in_status'] !=null)
        <tr>
            <td>{{ ++$loop->index }}.</td>
            @if(isset($row->getOldestAttendances))
                <td>
                    <a href="javascript:void(0)" class="attendancePhotoIdModal" data-id="{{ $row->getOldestAttendances['selfi_pic'] }}" data-toggle="modal" data-target="#viewPhotoModal">
                        <img src="{{ asset('public/upload/bpattendance/'.$row->getOldestAttendances['selfi_pic']) }}" alt="photo" width="50" height="50"/>
                    </a>
                </td>
                <td>{{ ($row->bp_name) ? $row->bp_name : '' }}</td>
                <td>{{ ($row->bp_phone) ? $row->bp_phone : '' }}</td>
				<td>{{ ($row->distributor_name) ? $row->distributor_name : '' }}</td>
				<td>{{ ($row->distributor_code) ? $row->distributor_code : '' }}</td>
                @if(isset($row->getLatestAttendances))
                <td>
                    <span class="badge badge-primary badge-sm"> 
                        {{ ($row->getLatestAttendances['in_status']) ? $row->getLatestAttendances['in_status']:$status  }}
                    </span>
                </td>
                @else
                <td>{{ '--' }}</td>
                @endif
                <td>
                    {{ ($row->getOldestAttendances['out_status']) ? $row->getOldestAttendances['out_status']:'' }}
                </td>
                <td>
                    {{ ($row->getLatestAttendances['location']) ? $row->getLatestAttendances['location']:'' }}
                </td>

                <td>
                    {{ ($row->getLatestAttendances['date']) ? $row->getLatestAttendances['date']:date('Y-m-d') }}
                </td>
                @if(isset($row->getLatestAttendances))
                <td>
                    {{ ($row->getLatestAttendances['date']) ? $row->getLatestAttendances['date']:''  }}<br/>
                    {{ ($row->getLatestAttendances['location']) ? $row->getLatestAttendances['location']:'' }}
                </td>
                @endif

                <td>
                    {{ ($row->getOldestAttendances['date']) ? $row->getOldestAttendances['date']:''  }}<br/>
                    {{ ($row->getOldestAttendances['location']) ? $row->getOldestAttendances['location']:'' }}
                </td>
                <td style="text-align: center;">
                    @if(empty($row->in_status))
                        <button type="button" data-id="{{ $row->id }}" class="btn btn-info btn-xs eyeViewbtn" disabled><i class="fa fa-eye" aria-hidden="true"></i></button>
                    @else
                        <a href="javascript:void(0)">
                            <button type="button" data-id="{{ $row->id }}" id="bpAttendanceDetails" class="btn cur-p btn-info btn-xs eyeViewbtn" data-toggle="modal" data-target="#viewAttendanceDetailsModal"><i class="fa fa-eye" aria-hidden="true"></i></button>
                        </a>
                    @endif
                </td>
            @elseif(isset($row->getLatestAttendances))
                <td>
                    <a href="javascript:void(0)" class="attendancePhotoIdModal" data-id="{{ $row->getLatestAttendances['selfi_pic'] }}" data-toggle="modal" data-target="#viewPhotoModal">
                        <img src="{{ asset('public/upload/bpattendance/'.$row->getLatestAttendances['selfi_pic']) }}" alt="photo" width="50" height="50"/>
                    </a>
                </td>
                <td>{{ ($row->bp_name) ? $row->bp_name : '' }}</td>
                <td>{{ ($row->bp_phone) ? $row->bp_phone : '' }}</td>
				<td>{{ ($row->distributor_name) ? $row->distributor_name : '' }}</td>
				<td>{{ ($row->distributor_code) ? $row->distributor_code : '' }}</td>
                <td>
                    <span class="badge badge-primary badge-sm"> 
                        {{ ($row->getLatestAttendances['in_status']) ? $row->getLatestAttendances['in_status']:$status  }}
                    </span>
                </td>
                <td>{{ ($row->getLatestAttendances['out_status']) ? $row->getLatestAttendances['out_status']:'' }}</td>
                <td>{{ ($row->getLatestAttendances['location']) ? $row->getLatestAttendances['location']:'' }}</td>
                <td>{{ ($row->getLatestAttendances['date']) ? $row->getLatestAttendances['date']:date('Y-m-d') }}</td>
                <td>
                    {{ ($row->getLatestAttendances['date']) ? $row->getLatestAttendances['date']:''  }}<br/>
                    {{ ($row->getLatestAttendances['location']) ? $row->getLatestAttendances['location']:'' }}
                </td>
                <td>
                    {{ ($row->getLatestAttendances['date']) ? $row->getLatestAttendances['date']:''  }}<br/>
                    {{ ($row->getLatestAttendances['location']) ? $row->getLatestAttendances['location']:''  }}
                </td>
                <td style="text-align: center;">
                    @if(empty($row->getLatestAttendances['in_status']))
                        <button type="button" data-id="{{ $row->getLatestAttendances['bp_id'] }}" class="btn btn-info btn-xs eyeViewbtn" disabled><i class="fa fa-eye" aria-hidden="true"></i></button>
                    @else
                        <a href="javascript:void(0)">
                            <button type="button" data-id="{{ $row->getLatestAttendances['bp_id'] }}" id="bpAttendanceDetails" class="btn cur-p btn-info btn-xs eyeViewbtn" data-toggle="modal" data-target="#viewAttendanceDetailsModal"><i class="fa fa-eye" aria-hidden="true"></i></button>
                        </a>
                    @endif
                </td>
            @else
                <td>
                    <img src="{{ asset('public/upload/client/no-image.png') }}" alt="photo" width="50" height="50"/>
                </td>
                <td>{{ ($row->bp_name) ? $row->bp_name : '' }}</td>
                <td>{{ ($row->bp_phone) ? $row->bp_phone : '' }}</td>
				<td>{{ ($row->distributor_name) ? $row->distributor_name : '' }}</td>
				<td>{{ ($row->distributor_code) ? $row->distributor_code : '' }}</td>
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
                <td style="text-align: center;"></td>
            @endif
        </tr>
        @endif
    @elseif($getOrderBy == "absent")
        @if(empty($row->getLatestAttendances))
        <tr>
            <td>{{ ++$loop->index }}.</td>
            @if(isset($row->getOldestAttendances))
                <td>
                    <a href="javascript:void(0)" class="attendancePhotoIdModal" data-id="{{ $row->getOldestAttendances['selfi_pic'] }}" data-toggle="modal" data-target="#viewPhotoModal">
                        <img src="{{ asset('public/upload/bpattendance/'.$row->getOldestAttendances['selfi_pic']) }}" alt="photo" width="50" height="50"/>
                    </a>
                </td>
                <td>{{ ($row->bp_name) ? $row->bp_name : '' }}</td>
                <td>{{ ($row->bp_phone) ? $row->bp_phone : '' }}</td>
				<td>{{ ($row->distributor_name) ? $row->distributor_name : '' }}</td>
				<td>{{ ($row->distributor_code) ? $row->distributor_code : '' }}</td>
                @if(isset($row->getLatestAttendances))
                <td>
                    <span class="badge badge-primary badge-sm"> 
                        {{ ($row->getLatestAttendances['in_status']) ? $row->getLatestAttendances['in_status']:$status  }}
                    </span>
                </td>
                @else
                <td>{{ '--' }}</td>
                @endif
                <td>
                    {{ ($row->getOldestAttendances['out_status']) ? $row->getOldestAttendances['out_status']:'' }}
                </td>
                <td>
                    {{ ($row->getLatestAttendances['location']) ? $row->getLatestAttendances['location']:'' }}
                </td>

                <td>
                    {{ ($row->getLatestAttendances['date']) ? $row->getLatestAttendances['date']:date('Y-m-d') }}
                </td>
                @if(isset($row->getLatestAttendances))
                <td>
                    {{ ($row->getLatestAttendances['date']) ? $row->getLatestAttendances['date']:''  }}<br/>
                    {{ ($row->getLatestAttendances['location']) ? $row->getLatestAttendances['location']:'' }}
                </td>
                @endif

                <td>
                    {{ ($row->getOldestAttendances['date']) ? $row->getOldestAttendances['date']:''  }}<br/>
                    {{ ($row->getOldestAttendances['location']) ? $row->getOldestAttendances['location']:'' }}
                </td>
                <td style="text-align: center;">
                    @if(empty($row->in_status))
                        <button type="button" data-id="{{ $row->id }}" class="btn btn-info btn-xs eyeViewbtn" disabled><i class="fa fa-eye" aria-hidden="true"></i></button>
                    @else
                        <a href="javascript:void(0)">
                            <button type="button" data-id="{{ $row->id }}" id="bpAttendanceDetails" class="btn cur-p btn-info btn-xs eyeViewbtn" data-toggle="modal" data-target="#viewAttendanceDetailsModal"><i class="fa fa-eye" aria-hidden="true"></i></button>
                        </a>
                    @endif
                </td>
            @elseif(isset($row->getLatestAttendances))
                <td>
                    <a href="javascript:void(0)" class="attendancePhotoIdModal" data-id="{{ $row->getLatestAttendances['selfi_pic'] }}" data-toggle="modal" data-target="#viewPhotoModal">
                        <img src="{{ asset('public/upload/bpattendance/'.$row->getLatestAttendances['selfi_pic']) }}" alt="photo" width="50" height="50"/>
                    </a>
                </td>
                <td>{{ ($row->bp_name) ? $row->bp_name : '' }}</td>
                <td>{{ ($row->bp_phone) ? $row->bp_phone : '' }}</td>
				<td>{{ ($row->distributor_name) ? $row->distributor_name : '' }}</td>
				<td>{{ ($row->distributor_code) ? $row->distributor_code : '' }}</td>
                <td>
                    <span class="badge badge-primary badge-sm"> 
                        {{ ($row->getLatestAttendances['in_status']) ? $row->getLatestAttendances['in_status']:$status  }}
                    </span>
                </td>
                <td>{{ ($row->getLatestAttendances['out_status']) ? $row->getLatestAttendances['out_status']:'' }}</td>
                <td>{{ ($row->getLatestAttendances['location']) ? $row->getLatestAttendances['location']:'' }}</td>
                <td>{{ ($row->getLatestAttendances['date']) ? $row->getLatestAttendances['date']:date('Y-m-d') }}</td>
                <td>
                    {{ ($row->getLatestAttendances['date']) ? $row->getLatestAttendances['date']:''  }}<br/>
                    {{ ($row->getLatestAttendances['location']) ? $row->getLatestAttendances['location']:'' }}
                </td>
                <td>
                    {{ ($row->getLatestAttendances['date']) ? $row->getLatestAttendances['date']:''  }}<br/>
                    {{ ($row->getLatestAttendances['location']) ? $row->getLatestAttendances['location']:''  }}
                </td>
                <td style="text-align: center;">
                    @if(empty($row->getLatestAttendances['in_status']))
                        <button type="button" data-id="{{ $row->getLatestAttendances['bp_id'] }}" class="btn btn-info btn-xs eyeViewbtn" disabled><i class="fa fa-eye" aria-hidden="true"></i></button>
                    @else
                        <a href="javascript:void(0)">
                            <button type="button" data-id="{{ $row->getLatestAttendances['bp_id'] }}" id="bpAttendanceDetails" class="btn cur-p btn-info btn-xs eyeViewbtn" data-toggle="modal" data-target="#viewAttendanceDetailsModal"><i class="fa fa-eye" aria-hidden="true"></i></button>
                        </a>
                    @endif
                </td>
            @else
                <td>
                    <img src="{{ asset('public/upload/client/no-image.png') }}" alt="photo" width="50" height="50"/>
                </td>
                <td>{{ ($row->bp_name) ? $row->bp_name : '' }}</td>
                <td>{{ ($row->bp_phone) ? $row->bp_phone : '' }}</td>
				<td>{{ ($row->distributor_name) ? $row->distributor_name : '' }}</td>
				<td>{{ ($row->distributor_code) ? $row->distributor_code : '' }}</td>
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
                <td style="text-align: center;"></td>
            @endif
        </tr>
        @endif
    @endif
    
    @endforeach
@endif
