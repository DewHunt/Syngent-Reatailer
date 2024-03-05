<div id="tag_container" class="table-responsive">
    <table id="example3" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th width="20px">Sl.</th>
                <th>Zone Name</th>
                <th width="80px" class="text-center">Status</th>
                <th width="100px" class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($ZoneList))
                @foreach($ZoneList as $row)
                <tr class="row_{{ $row->id }}">
                    <td>{{ ++$loop->index }}.</td>
                    <td>{{ $row->zone_name }}</td>
                    <td>
                        <input data-id="{{ $row->id }}" class="zone-toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" @if($row->status == 1 )checked @else {{ ' ' }}@endif>
                    </td>
                    <td class="text-center">
                        <button type="button" data-id="{{ $row->id }}" id="editZoneInfo" class="btn btn-primary" data-toggle="modal" data-target="#editZoneModal"><i class="fa fa-edit"></i></button>
                        <button type="button" data-id="{{ $row->id }}" id="deleteZone" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
    


                    