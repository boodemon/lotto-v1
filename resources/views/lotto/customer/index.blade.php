@extends('lotto.layouts.template')
@section('stylesheet')
	<link rel="stylesheet" href="{{ asset('public/build/css/customer-index.css') }}" />
@endsection
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
	<table id="example" class="table table-striped table-bordered responsive-utilities jambo_table">
		<thead>
			<tr class="headings">
				<th scope="col" class="w40"><input type="checkbox" id="checkAll" class="tableflat"/></th>
				<th class="w120">ชื่อผู้ซื้อ</th>
				<th class="">เลขที่ซื้อ</th>
				<th class="w120">จำนวนเงิน</th>
				<th class="w120">จ่ายแล้ว</th>
				<th class="w120">คงเหลือ</th>
				<th class="w180">อัพเดทล่าสุด</th>
				<th class="w100 tableflat">Action</th>
			</tr>
		</thead>
		<tbody>
			@if($rows)
				@foreach($rows as $row)
				<?php $user = $row->id;?>
					<tr>
						<td>
							<input name="id[]" type="checkbox" id="id" value="{{ $row->id }}" class="checkboxAll" />
						</td>
						<td>{{ $row->name }}</td>
						<td> {!! Lib::numberTag( $number[$user] ) !!}</td>
						<td class="text-right">{{ $row->total }}</td>
						<td class="text-right">{{ $row->paid }}</td>
						<td class="text-right">{{ $row->remain }}</td>
						<td>{{ Lib::datethai($row->created_at) }}</td>
						<td class="action">
							<a href="{{url('customer/'. $row->id .'/edit' )}}"><span class="fa fa-edit color-green fs-16"></span></a>&nbsp;
							<a href="{{url('customer/'. $row->id )}}"class="fa fa-trash txtred fs-16 del"></a>&nbsp;
						</td>
					</tr>
						
				@endforeach
			@endif
		</tbody>
	</table>
@endsection