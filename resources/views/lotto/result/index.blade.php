@extends('lotto.layouts.template')
@section('stylesheet')
	<link rel="stylesheet" href="{{ asset('public/build/css/customer-index.css') }}" />
@endsection
@section('right-search')
@endsection

@section('action-button')
	<ul class="nav navbar-right panel_toolbox">
		<li>
			<a href="{{url('result/create')}}" class="btn btn-success"><i class="fa fa-plus"></i> เพิ่มผลรางวัล</a>
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
				<th class="w120">งวดวันที่</th>
				<th class="">3 ตัวบน(เต็ง)</th>
				<th class="w120">3 ตัวโต๊ด</th>
				<th class="w120">2 ตัวบน</th>
				<th class="w120">2 ตัวโต๊ด</th>
				<th class="w180">วิ่งบน</th>
				<th class="w180">2 ตัวล่าง</th>
				<th class="w180">วิ่งล่าง</th>
				<th class="w180">3 ตัวล่าง</th>
				<th class="w100 tableflat">Action</th>
			</tr>
		</thead>
		<tbody>
			@if($rows)
				@foreach($rows as $row)
					<tr>
						<td>
							<input name="id[]" type="checkbox" id="id" value="{{ $row->id }}" class="checkboxAll" />
						</td>
						<td>{{ $row->peroid }}</td>
						<td class="text-right">{{ $row->tang }}</td>
						<td class="text-right">{{ $row->tod }}</td>
						<td class="text-right">{{ $row->uptwo }}</td>
						<td class="text-right">{{ $row->todtwo }}</td>
						<td class="text-right">{{ $row->upwing }}</td>
						<td class="text-right">{{ $row->downtwo }}</td>
						<td class="text-right">{{ $row->downtree }}</td>
						<td class="text-right">{{ $row->downwing }}</td>
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