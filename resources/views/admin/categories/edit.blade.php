@extends('admin.master.master')

@section('page-style')
@endsection

@section('content')
	<div class="card">
		<div class="card-header">
			<div class="row">
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12"><h3>Add Category</h3></div>
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
					<a class="btn btn-primary pull-right" href="{{ route('category.index') }}">
						<i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;Category List
					</a>
				</div>
			</div>
		</div>

		<div class="card-body">
            <form class="form-horizontal" method="POST" action="{{ route('category.update') }}" id="add-category">
                @csrf
                <input type="hidden" name="categoryId" class="form-control" required="" value="{{ $categoryInfo->id }}" />
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Category Name <span class="required">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Category Name" required="" value="{{ $categoryInfo->name }}" />
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    	<label>Status</label>
                        <div class="form-group">
                            <div class="form-check-inline">
                            	<label class="form-check-label">
                            		<input type="radio" class="form-check-input" name="status" value="1" {{ $categoryInfo->status == 1 ? 'checked' : '' }}>Active
                            	</label>
                            </div>
                            <div class="form-check-inline">
                            	<label class="form-check-label">
                            		<input type="radio" class="form-check-input" name="status" value="0" {{ $categoryInfo->status == 0 ? 'checked' : '' }}>Inactive
                            	</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group"> 
                            <button type="submit" class="btn btn-primary pull-right">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
		</div>
	</div>
@endsection

@section('page-scripts')
@endsection