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
		<label class="col-md-2 control-label">ชื่อผู้ซื้อ</label>
		<div class="col-md-4">
			<input type="text" class="form-control" name="name" value="{{ $row ? $row->name : old('name') }}">
			{!!$errors->first('name', '<span class="control-label color-red" for="name">*:message</span>')!!}
		</div>
		<label class="col-md-2 control-label">ประจำงวด</label>
		<div class="col-md-4">
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
					<td><span class="sum">0</span></td>
					<td class="text-center">
						<button class="btn btn-sm btn-success btn-add" style="margin-top:-5px;"><i class="fa fa-plus"></i></button>
					</td>
				</tr>
				<tr>
					<td colspan="4" class="text-right"><strong>รวมเป็นเงิน</strong></td>
					<td class="total">0</td>
					<td></td>
			</tbody>
		
		</table>
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