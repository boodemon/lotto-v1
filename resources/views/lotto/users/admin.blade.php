@extends('lotto.layouts.template')
@section('right-search')
	<form method="get" action="{{ url('user') }}">
			<div class="input-group">
				<input type="text" class="form-control" name="keywords" value="{{ Request::input('keywords') }}" placeholder="Search for...">
				<span class="input-group-btn">
					<button class="btn btn-default" type="submit">Go!</button>
				</span>
			</div>
	</form>
@endsection

@section('action-button')
	<ul class="nav navbar-right panel_toolbox">
		<li>
			<a href="{{url('user/create')}}" class="btn btn-success"><i class="fa fa-plus"></i> Add New</a>
		</li>
		<li>
			<button type="submit" name="btn-delete" class="btn btn-danger del"><i class="fa fa-remove"></i> Delete</button>
		</li>
	</ul>
@endsection

@section('content')
	<input type="hidden" name="_method" value="PUT">
	<table id="example" class="table table-striped table-bordered responsive-utilities jambo_table">
		<thead>
			<tr class="headings">
				<th scope="col" class="w40"><input type="checkbox" id="checkAll" class="tableflat"/></th>
				<th class="w120">Username</th>
				<th>E-mail</th>
				<th>Name</th>
				<th>Position</th>
				<th>Level</th>
				<th class="w160">Last Update</th>
				<th class="w100 tableflat">Action</th>
			</tr>
		</thead>
		<tbody>
		@if($users)
			@foreach($users as $u)
			<tr class="even pointer odd gradeX">
				<td class="text-center"><input name="id[]" type="checkbox" id="id" value="{{ $u->id }}" class="checkboxAll {{ $u->id == 1 ? 'hide' : '' }}" /></td>
				<td>{{ $u->username }}</td>
				<td>{{$u->email}}</td>
				<td>{{$u->name}}</td>
				<td>{{Lib::level($u->position) }}</td>
				<td>{{ Lib::level($u->type) }}</td>
				<td>{{ $u->updated_at }}</td>
				<td class="action">
					<a href="{{url('user/'. $u->id .'/edit' )}}"><span class="glyphicon glyphicon-edit color-green fs-16"></span></a>&nbsp;
					<a href="{{url('user/'. $u->id  )}}" class="{{ $u->id == 1 ? 'hide' : '' }} del"><span class="fa fa-trash color-red fs-16"></span></a>&nbsp;
				</td>
			</tr>
			@endforeach
		@endif
		</tbody>
	</table>
	<div class="text-right">
		<?php 
			if(Request::exists('keywords')){
				$users->appends(['keywords' => Request::input('keywords')]);
			}
		?>
		{!! $users->links() !!}
	</div>
@endsection
