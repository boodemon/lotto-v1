@extends('lotto.layouts.template')
@section('stylesheet')
	<link rel="stylesheet" href="{{ asset('public/lib/jquery-ui-1.12.1.custom/jquery-ui.min.css') }}" />
@endsection
@section('action-button')
	<ul class="nav navbar-right panel_toolbox">
		<li>
			<a href="{{url('result')}}" class="btn btn-default btn-back"><i class="fa fa-angle-left"></i> Back</a>
		</li>

	</ul>
@endsection
@section('content')
	@if($id != 0)
		<input type="hidden" name="_method" value="PUT">
	@endif
		
	<div class="form-group">
		<label class="col-md-2 control-label">ผลการออกสลาก ประจำงวดวันที่</label>
		<div class="col-md-3">
			<select name="peroid" class="form-control">
				@if($dues)
					@foreach($dues as $due)
						<option value="{{ $due->id }}" >{{ Lib::dateThai( $due->ondate ) }}</option>
					@endforeach
				@endif
			</select>
			{!!$errors->first('peroid', '<span class="control-label color-red" for="peroid">*:message</span>')!!}
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-2 control-label">3 ตัวบน</label>
		<div class="col-md-2">
			<input type="text" class="form-control" name="tang" value="">
			{!!$errors->first('tang', '<span class="control-label color-red" for="tang">*:message</span>')!!}
		</div>
	</div>		
	
	<div class="form-group">
		<label class="col-md-2 control-label">2 ตัวบน</label>
		<div class="col-md-2">
			<input type="text" class="form-control" name="uptwo" value="">
			{!!$errors->first('uptwo', '<span class="control-label color-red" for="uptwo">*:message</span>')!!}
		</div>
	</div>	
	
	<div class="form-group">
		<label class="col-md-2 control-label">3 ตัวล่าง</label>
		<div class="col-md-2">
			<input type="text" class="form-control" name="downtree[]" value="">
		</div>
		<div class="col-md-2">
			<input type="text" class="form-control" name="downtree[]" value="">
		</div>
		<div class="col-md-2">
			<input type="text" class="form-control" name="downtree[]" value="">
		</div>
		<div class="col-md-2">
			<input type="text" class="form-control" name="downtree[]" value="">
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-md-2 control-label">2 ตัวล่าง</label>
		<div class="col-md-2">
			<input type="text" class="form-control" name="downtwo" value="">
		</div>
	</div>


	<div class=" form-group">
		<label class="col-md-2 control-label"></label>
		<div class="col-md-2">
		<button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> บันทึก</button>
		</div>
	</div>
	
@endsection
@section('javascripts')
	<script src="{{ asset('public/lib/jquery-ui-1.12.1.custom/jquery-ui.js') }}"></script>
    <script src="{{ asset('public/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js') }}"></script>
 	<script type="text/javascript" src="{{ asset('public/build/js/customer-form.js') }}"></script>
@endsection