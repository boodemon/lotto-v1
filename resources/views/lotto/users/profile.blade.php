@extends('lostrip.layouts.template')

@section('action-button')
	<ul class="nav navbar-right panel_toolbox">
		<li>
		</li>

	</ul>
@endsection

@section('content')
	<div class="" role="tabpanel" data-example-id="togglable-tabs">
		<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
			<li role="presentation" class="active"><a href="#tab_order" id="order-tab" role="tab" data-toggle="tab" aria-expanded="true">Order History</a>
			</li>
			<li role="presentation" class=""><a href="#tab_profile" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Profile</a>
			</li>
		</ul>
		<div id="myTabContent" class="tab-content">
			<div role="tabpanel" class="tab-pane fade active in" id="tab_order" aria-labelledby="order-tab">
                @include('lostrip.users.inc-tab-order')
			</div>
			<div role="tabpanel" class="tab-pane fade" id="tab_profile" aria-labelledby="profile-tab">
                @include('lostrip.users.inc-tab-profile');
			</div>
		</div>
	</div>
@endsection
@section('javascript')
    <script src="{{ asset('public/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('public/build/js/customer-index.js') }}"></script>
	<script src="{{asset('public/admin/js/tools/image.js')}}" type="text/javascript"></script>
	<script src="{{asset('public/admin/js/admin-profile.js')}}" type="text/javascript"></script>
@endsection