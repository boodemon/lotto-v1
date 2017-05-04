@extends('lotto.layouts.template')
@section('content')
			<form method="POST" action="{{url('admin/users/action')}}">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<div class="x_panel">
                    <div class="x_title">
						<h2>จัดการข้อมูลตัวแทนขาย</h2>
						<div class="nav navbar-right panel_toolbox">
							<a href="{{url('/admin/users/form/')}}" class="btn btn-success" box-width="800" box-height="500"><i class="fa fa-plus"></i> Add New</a>
							<button type="submit" class="btn btn-danger del"><i class="fa fa-remove"></i> Delete</button>
                        </div>
                    </div>
					 <div class="x_content">
                        <table id="example" class="table table-striped table-bordered responsive-utilities jambo_table">
							<thead>
								<tr class="headings">
									<th scope="col" class="w40"><input type="checkbox" id="checkAll" class="tableflat"/></th>
                                    <th class="w120">Username</th>
                                    <th>ชื่อ - สกุล</th>
                                    <th class="w160">เบอร์โทร</th>
                                    <th class="w160">อัพเดทล่าสุด</th>
                                    <th class="w100 tableflat">Action</th>

                                 </tr>
                            </thead>
							<tbody>
 									@if($rows)
									@foreach($rows as $u)
                                        <tr class="even pointer odd gradeX">
											<td class="text-center"><input name="id[]" type="checkbox" id="id" value="{{ $u->id }}" class="checkboxAll" /></td>
											<td>{{ $u->username }}</td>
                                            <td>{{$u->email}}</td>
                                            <td>{{$u->name}}</td>
                                            <td>{{$u->tel}}</td>
                                            <td>{{ $u->updated_at }}</td>
                                            <td class="text-right">
												<a href="{{url('admin/users/form/'. $u->id )}}"><span class="glyphicon glyphicon-edit color-green fs-16"></span></a>&nbsp;
												<a href="{{url('admin/users/delete/'. $u->id )}}"class="glyphicon glyphicon-trash txtred fs-16 del"></a>&nbsp;
											</td>
                                        </tr>
									@endforeach
									@endif
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</form>
@endsection