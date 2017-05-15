
	<input type="hidden" name="id" value="{{$id}}">
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
		<label class="col-md-4 control-label">Tel.</label>
		<div class="col-md-4">
			<input type="text" class="form-control" name="tel" value="{{ !$user ? '' : $user->tel }}">
			{!!$errors->first('tel', '<span class="control-label color-red" for="tel">*:message</span>')!!}
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
		<label class="col-md-4 control-label">E-Mail</label>
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