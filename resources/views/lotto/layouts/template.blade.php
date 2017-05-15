<!DOCTYPE html>
<html lang="en">
	<head>
		@include('lotto.layouts.inc-head')
		@yield('stylesheet')
	</head>

	<body class="nav-md" ng-app="lotto">
		<div class="container body">
			<div class="main_container">
				<div class="col-md-3 left_col">
					<!-- sidebar menu -->
						@include('lotto.layouts.inc-sidebar')
					<!-- /sidebar menu -->
				</div>

				<!-- top navigation -->
					@include('lotto.layouts.inc-top-navigation')
				<!-- /top navigation -->

				<!-- page content -->
				<div class="right_col" role="main">
					<div class="">
						<div class="page-title">
							<div class="title_left">
								<h3>{!! isset($subject) ? $subject : '' !!}</h3>
							</div>

							<div class="title_right">
								<div class="col-md-7 col-sm-7 col-xs-12 form-group pull-right top_search">
									@yield('right-search')
								</div>
							</div>
						</div>

						<div class="clearfix"></div>

						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<form method="POST" action="{{ isset($actionUrl) ? url($actionUrl) : '' }}" enctype='multipart/form-data' class="form-horizontal">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">				
									<div class="x_panel">
										<div class="x_title">
											<h2>{{ isset($title) ? $title : '' }}</h2>
											@yield('action-button')
											<div class="clearfix"></div>
										</div>
										<div class="x_content">
											@yield('content')
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /page content -->

				<!-- footer content -->
				<footer class="footer_fixed">
					<div class="pull-right">
						lotto Management system
					</div>
					<div class="clearfix"></div>
				</footer>
				<!-- /footer content -->
			</div>
		</div>
		@include('lotto.layouts.inc-modal')
		@include('lotto.layouts.inc-script')
		@yield('javascripts')
	</body>
</html>