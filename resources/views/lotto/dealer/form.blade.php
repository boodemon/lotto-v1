@extends('lotto.layouts.template')

@section('content')
<div class="page-title">
	<div class="title_left">
		<h3>Profile of {{!$user ? '' : $user->name}}</h3>
	</div>

	<div class="title_right">
		<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">

		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel" style="min-height:600px;">
			<div class="x_title">
				<h2>Dashboard Data</h2>
				<a class="btn btn-default pull-right" href="{{ url('admin/user') }}"><i class="fa fa-angle-left"></i> Back</a>
			</div>
			<div class="x_content">
			
					<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/user' . ( $id != 0 ? '/' .$id : '' ) ) }}" enctype="multipart/form-data">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="id" value="{{$id}}">
						<input type="hidden" name="type" value="admin">
						@if($id != 0)
							<input type="hidden" name="_method" value="PUT">
						@endif
		
						<div class="form-group">
							<label class="col-md-4 control-label">Name</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="name" value="{{ !$user ? '' : $user->name }}">
								{!!$errors->first('name', '<span class="control-label color-red" for="name">*:message</span>')!!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Username</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="username" value="{{ !$user ? '' : $user->username }}">
								{!!$errors->first('username', '<span class="control-label color-red" for="username">*:message</span>')!!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">E-Mail Address</label>
							<div class="col-md-6">
								<input type="email" class="form-control" name="email" value="{{ !$user ? '' : $user->email }}">
								{!!$errors->first('email', '<span class="control-label color-red" for="email">*:message</span>')!!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password">
								{!!$errors->first('password', '<span class="control-label color-red" for="password">*:message</span>')!!}
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									<i class="fa fa-save"></i> SAVE
								</button>
							</div>
						</div>
					</form>
			
			</div>
		</div>
	</div>
</div>

@endsection
@section('javascript')
	<script src="{{asset('public/admin/js/tools/image.js')}}" type="text/javascript"></script>
	<script src="{{asset('public/admin/js/admin-profile.js')}}" type="text/javascript"></script>
@stop