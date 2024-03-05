<div class="table-responsive">
    <table id="example2" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th width="20px">Sl.</th>
                <th>Banner</th>
                <th>Status</th>
                <th width="70px">Action</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($bannerList))
                @foreach($bannerList as $row)
                    <tr class="row_{{ $row->id }}">
                        <td>{{ ++$loop->index }}.</td>
                        <td>
                            @if(isset($row->image_path) && !empty($row->image_path) && $row->image_path != null)
                                <a href="javascript:void(0)" class="bannerphotoIdModal" data-id="{{ $row->banner_pic }}" data-toggle="modal" data-target="#viewPhotoModal">
                                    <img src="{{ asset('public/upload/banner/thumbnail/'.$row->banner_pic)}}" alt="" width="380" height="150"/>
                                </a>
                            @endif
                        </td>
                        <td>
                            <input data-id="{{ $row->id }}" class="banner-toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" @if($row->status == 1 ) {{'checked'}} @else {{ 'in-checked' }}@endif>
                        </td>
                        <td>
                            <button type="button" data-id="{{ $row->id }}" id="editBannerInfo" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editBannerModal">Edit</button>
                            {{-- <button type="button" data-id="{{ $row->id }}" class="btn btn-danger btn-sm delete-img">Delete</button> --}}
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>