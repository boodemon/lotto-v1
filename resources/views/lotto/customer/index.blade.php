@extends('lotto.layouts.template')
@section('right-search')
	<form method="get" action="{{ url('customer') }}">
			<div class="input-group">
				<input type="text" class="form-control" name="keywords" value="{{ Request::input('keywords') }}" placeholder="ค้นหาชื่อผู้ซื้อ">
				<span class="input-group-btn">
					<button class="btn btn-default" type="submit">Go!</button>
				</span>
			</div>
	</form>
@endsection

@section('action-button')
	<ul class="nav navbar-right panel_toolbox">
		<li>
			<a href="{{url('customer/create')}}" class="btn btn-success"><i class="fa fa-plus"></i> เพิ่มรายการขาย</a>
		</li>
		<li>
			<button type="submit" name="btn-delete" class="btn btn-danger del"><i class="fa fa-remove"></i> Delete</button>
		</li>
	</ul>
@endsection
@section('content')
	
@endsection