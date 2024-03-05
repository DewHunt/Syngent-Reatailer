@extends('admin.master.master')

@section('page-style')
@endsection

@section('content')
    @php
        $message = Session::get('msg');
        $error = Session::get('error');
    @endphp

    @if (isset($message))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success!</strong> {{ $message }}
        </div>
    @endif

    @if (isset($error))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Oops!</strong> {{ $error }}
        </div>
    @endif

    @php
        Session::forget('msg');
        Session::forget('error');
    @endphp

	<div class="card">
		<div class="card-header">
			<div class="row">
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">Categories</div>
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
					<a class="btn btn-primary pull-right" href="{{ route('category.add') }}">
						<i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Add Category
					</a>
				</div>
			</div>
		</div>

		<div class="card-body">
			{{-- Category List --}}
			<div class="result-data">
				<div class="table-responsive">
					<table id="example2" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th width="20px">SL.</th>
								<th>Name</th>
								<th width="100px">Status</th>
								<th width="100px" class="text-center">Actions</th>
							</tr>
						</thead>

						<tbody>							
							@php
								$sl = 1;
							@endphp
							@if (isset($categories))
								@foreach ($categories as $category)
									@php
										$status = 'Active';
										if ($category->status == 0) {
											$status = 'Inactive';
										}
									@endphp
									<tr>
										<td>{{ $sl++ }}</td>
										<td>{{ $category->name }}</td>
										<td class="text-center">{{ $status }}</td>
										<td class="text-center">
											<a href="{{ route('category.edit',$category->id) }}" class="btn btn-outline-success btn-sm"><i class="fa fa-edit"></i></a>
											<a href="{{ route('category.delete',$category->id) }}" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></a>
										</td>
									</tr>
								@endforeach
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('page-scripts')
@endsection