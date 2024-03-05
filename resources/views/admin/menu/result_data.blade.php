<div id="tag_container" class="table-responsive">
    <table id="example2" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th width="30px">Sl.</th>
                <th>Name</th>
                <th>Parent</th>
                <th>Link</th>
                <th width="60px" class="text-center">Order By</th>
                <th width="100px" class="text-center">Status</th>
                <th width="80px" class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
			@if(isset($menuList))
				@foreach ($menuList as $row)
					<tr class="row_{{ $row->id }}">
						<td>{{ ++$loop->index }}.</td>
						<td>{{ $row->menu_name }}</td>
						<td>{{ $row->parentName }}</td>
						<td>{{ $row->menu_link }}</td>
						<td class="text-center">{{ $row->order_by }}</td>
						<td class="text-center">
							<input data-id="{{ $row->id }}" class="menu-toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" @if($row->status == 1 )checked @else {{ ' ' }}@endif>
						</td>
						<td class="text-center">
							<button type="button" data-id="{{ $row->id }}" id="editmenu" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editmenuModal"><i class="fa fa-edit"></i></button>
							<button type="button" data-id="{{ $row->id }}" id="deletemenu" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
						</td>
					</tr>
				@endforeach
			@endif
        </tbody>
    </table>
</div>
    


                    