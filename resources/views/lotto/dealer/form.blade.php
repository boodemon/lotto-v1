@extends('lotto.layouts.template')

@section('action-button')
	<ul class="nav navbar-right panel_toolbox">
		<li>
			<a href="{{url('user')}}" class="btn btn-default btn-back"><i class="fa fa-angle-left"></i> Back</a>
		</li>

	</ul>
@endsection

@section('content')
	<input type="hidden" name="id" value="{{$id}}">
	@if($id != 0)
		<input type="hidden" name="_method" value="PUT">
	@endif
		
	<div class="form-group">
		<label class="col-md-4 control-label">Name</label>
		<div class="col-md-6">
			<input type="text" class="form-control" name="name" value="{{ !$row ? '' : $row->name }}">
			{!!$errors->first('name', '<span class="control-label color-red" for="name">*:message</span>')!!}
		</div>
	</div>
		
	<div class="form-group {{ Auth::guard('admin')->user()->type != 'admin' ? 'hide' : '' }}">
		<label class="col-md-4 control-label">Position</label>
		<div class="col-md-4">
			<select name="position" class="form-control">
				<option value="dealer" {{ ($row && $row->type == 'dealer') ? 'selected' : '' }}>ตัวแทนขาย</option>
				<option value="admin" {{ ($row && $row->type == 'admin') ? 'selected' : '' }}>ผู้ดูแลระบบ</option>
			</select>
			<!--
			<input type="text" class="form-control" name="position" value="{{ !$row ? '' : $row->position }}">
			-->
			{!!$errors->first('position', '<span class="control-label color-red" for="position">*:message</span>')!!}
		</div>
	</div>
		
	<div class="form-group">
		<label class="col-md-4 control-label">Tel.</label>
		<div class="col-md-4">
			<input type="text" class="form-control" name="tel" value="{{ !$row ? '' : $row->tel }}">
			{!!$errors->first('tel', '<span class="control-label color-red" for="tel">*:message</span>')!!}
		</div>
	</div>

	<div class="form-group">
		<label class="col-md-4 control-label">Username</label>
		<div class="col-md-6">
			<input type="text" class="form-control" name="username" value="{{ !$row ? '' : $row->username }}">
			{!!$errors->first('username', '<span class="control-label color-red" for="username">*:message</span>')!!}
		</div>
	</div>

	<div class="form-group">
		<label class="col-md-4 control-label">E-Mail</label>
		<div class="col-md-6">
			<input type="email" class="form-control" name="email" value="{{ !$row ? '' : $row->email }}">
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
@endsection
@section('javascript')
	<script src="{{asset('public/admin/js/tools/image.js')}}" type="text/javascript"></script>
	<script src="{{asset('public/admin/js/admin-profile.js')}}" type="text/javascript"></script>
@endsection