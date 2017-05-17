@extends('lotto.layouts.template')
@section('content')
	<div class="col-md-8 col-md-offset-2 col-sm-8 col-xs-12">
		<div class="form-group">
			<label class="control-label col-md-3">3 ตัวบน(เต็ง) บาทละ</label>
			<div class="col-md-6">
				<input type="text" name="tang" value="{{ $row ? $row->tang : old('tang') }}" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3">3 ตัวโต๊ด บาทละ</label>
			<div class="col-md-6">
				<input type="text" name="tod" value="{{ $row ? $row->tod : old('tod') }}" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3">2 ตัวบน บาทละ</label>
			<div class="col-md-6">
				<input type="text" name="uptwo" value="{{ $row ? $row->uptwo : old('uptwo') }}" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3">2 ตัวโต๊ด บาทละ</label>
			<div class="col-md-6">
				<input type="text" name="todtwo" value="{{ $row ? $row->todtwo : old('todtwo') }}" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3">วิ่งบน บาทละ</label>
			<div class="col-md-6">
				<input type="text" name="upwing" value="{{ $row ? $row->upwing : old('upwing') }}" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3">2 ตัวล่าง บาทละ</label>
			<div class="col-md-6">
				<input type="text" name="downtwo" value="{{ $row ? $row->downtwo : old('downtwo') }}" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3">วิ่งล่าง บาทละ</label>
			<div class="col-md-6">
				<input type="text" name="downtree" value="{{ $row ? $row->downtree : old('downtree') }}" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3">3 ตัวล่าง บาทละ</label>
			<div class="col-md-6">
				<input type="text" name="downwing" value="{{ $row ? $row->downwing : old('downwing') }}" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3">ค่าตอบแทน ตัวแทนขาย ร้อยละ</label>
			<div class="col-md-6">
				<input type="text" name="comission" value="{{ $row ? $row->comission : old('comission') }}" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3">&nbsp;</label>
			<div class="col-md-6">
				<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> บันทึก</button>
			</div>
		</div>
	</div>
@endsection