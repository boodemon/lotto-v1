@extends('lotto.layouts.template')
@section('stylesheet')
	<link rel="stylesheet" href="{{ asset('public/lib/jquery-ui-1.12.1.custom/jquery-ui.min.css') }}" />
@endsection
@section('action-button')
	<ul class="nav navbar-right panel_toolbox">
		<li>
			<a href="{{url('customer')}}" class="btn btn-default btn-back"><i class="fa fa-angle-left"></i> Back</a>
		</li>

	</ul>
@endsection
@section('content')
	@if($id != 0)
		<input type="hidden" name="_method" value="PUT">
	@endif
		
	<div class="form-group">
		<label class="col-md-1 control-label">ผู้ซื้อ</label>
		<div class="col-md-3">
			<input type="text" class="form-control" name="name" value="{{ $row ? $row->name : old('name') }}">
			{!!$errors->first('name', '<span class="control-label color-red" for="name">*:message</span>')!!}
		</div>
		<label class="col-md-1 control-label">ตัวแทน</label>
		<div class="col-md-3">
			<select name="dealer_id" class="form-control">
				@if($dealer)
					@foreach($dealer as $deal)
						<option value="{{ $deal->id }}" {{ $deal->id == $user->id ? 'selected' : '' }}>{{ $deal->name }}</option>
					@endforeach
				@endif
			</select>
			{!! $errors->first('dealer_id', '<span class="control-label color-red" for="dealer_id">*:message</span>') !!}
		</div>
		<label class="col-md-1 control-label">งวดวันที่</label>
		<div class="col-md-3">
			<input type="text" class="form-control" name="peroid" value="{{ $peroid }}">
			{!!$errors->first('peroid', '<span class="control-label color-red" for="peroid">*:message</span>')!!}
		</div>
	</div>
	<div class="table-responsive">
		<table class="table table-striped table-bordered responsive-utilities jambo_table">
			<thead>
				<th class="w220">เลขที่ซื้อ</th>
				<th class="">จำนวนเงิน</th>
				<th class="w10 text-center">x</th>
				<th class="">จำนวนเงิน</th>
				<th class="w220">รวม</th>
				<th class="w10">#</th>
			</thead>
			<tbody>
				<tr>
					<td><input type="text" class="form-control number" name="number[]" value=""/></td>
					<td><input type="text" class="form-control tang" name="tang[]" value=""/></td>
					<td class="text-center">x</td>
					<td><input type="text" class="form-control tod" name="tod[]" value=""/></td>
					<td class="text-right"><span class="sum">0</span></td>
					<td class="text-center">
						<button class="btn btn-sm btn-success btn-add" style="margin-top:-5px;"><i class="fa fa-plus"></i></button>
					</td>
				</tr>
				<tr>
					<td colspan="4" class="text-right"><strong>รวมเป็นเงิน</strong></td>
					<td class="total text-right">0</td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</div>
	
	<div class="form-group">
		<label class="col-md-2 control-label">จ่าย</label>
		<div class="col-md-4">
			<input type="text" class="form-control" name="paid" value="{{ $row ? $row->paid : old('paid') }}">
			{!!$errors->first('paid', '<span class="control-label color-red" for="paid">*:message</span>')!!}
		</div>
		<label class="col-md-2 control-label">คงเหลือ</label>
		<div class="col-md-4">
			<input type="text" class="form-control" name="remain" readonly value="{{ $row ? $row->remain : old('remain') }}">
			{!!$errors->first('remain', '<span class="control-label color-red" for="remain">*:message</span>')!!}
		</div>
	</div>

	<div class="text-right form-group">
		<button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> บันทึก</button>
	</div>
	
@endsection
@section('javascripts')
	<script src="{{ asset('public/lib/jquery-ui-1.12.1.custom/jquery-ui.js') }}"></script>
    <script src="{{ asset('public/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js') }}"></script>
 	<script type="text/javascript" src="{{ asset('public/build/js/customer-form.js') }}"></script>
@endsection