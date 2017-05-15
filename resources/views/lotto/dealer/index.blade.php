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
			<a href="{{url('dealer/create')}}" class="btn btn-success"><i class="fa fa-plus"></i> Add New</a>
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
				<th class="w120">Email</th>
				<th>ชื่อ - สกุล</th>
				<th class="w160">เบอร์โทร</th>
				<th class="w160">ประเภท</th>
				<th class="w160">อัพเดทล่าสุด</th>
				<th class="w100 tableflat">Action</th>
			</tr>
		</thead>
		<tbody>
		@if($rows)
			@foreach($rows as $u)
			<tr class="even pointer odd gradeX">
				<td class="text-center">
				@if($u->id != '1')
					<input name="id[]" type="checkbox" id="id" value="{{ $u->id }}" class="checkboxAll" />
				@endif
				</td>
				<td>{{ $u->username }}</td>
				<td>{{ $u->email }}</td>
				<td>{{$u->name}}</td>
				<td>{{$u->tel}}</td>
				<td>{{$u->type}}</td>
				<td>{{ date('d/m/Y H:i',strtotime($u->updated_at)) }}</td>
				<td class="text-right">
				
					<a href="{{url('dealer/'. $u->id .'/edit' )}}"><span class="glyphicon glyphicon-edit color-green fs-16"></span></a>&nbsp;
					@if($u->id != '1')
					<a href="{{url('dealer/'. $u->id )}}"class="glyphicon glyphicon-trash txtred fs-16 del"></a>&nbsp;
					@endif
				</td>
			</tr>
			@endforeach
		@endif
		</tbody>
	</table>
	<div class="text-right">
		<?php 
			if(Request::exists('keywords')){
				$rows->appends(['keywords' => Request::input('keywords')]);
			}
		?>
		{!! $rows->links() !!}
	</div>
@endsection
