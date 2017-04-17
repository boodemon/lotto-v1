<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Administrators Backoffice</title>
	<link rel="icon" href="{{url('/public/admin/images/icon-logo.png')}}" type="image/png">
	<link rel="shortcut icon" href="{{url('/public/admin/images/icon-logo.png')}}" type="image/png">

	<link href="{{ asset('/public/admin/css/admin-login.css') }}" rel="stylesheet">
	<link href="{{ asset('public/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" media="all" />

	<!-- Fonts -->

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<div class="container">
		<div class="row">
			<form class="form-signin mg-btm form-horizontal" role="form" method="POST" action="{{ url('/admin/login') }}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<h3 class="heading-desc">Administrators Login</h3>

			@if (count($errors) > 0)
				<div class="alert alert-danger">
					<strong>Whoops!</strong> There were some problems with your input.<br><br>
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
			<div class="main">	
				<label>Username</label>    
				<div class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
					<input type="text" class="form-control" name="email" value="{{ old('email') }}" placeholder="Username Or Email" autofocus>
				</div>
				<label>Password</label>
				<div class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
					<input type="password" class="form-control" name="password" placeholder="Password">
				</div>

				<div class="form-group">
					<div class="col-md-12">
						<div class="checkbox">
							<label>
								<input type="checkbox" name="remember"> Remember Me
							</label>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-6 col-md-6">
						 
					</div>
					<div class="col-xs-6 col-md-6 pull-right">
						<button type="submit" class="btn btn-large btn-success pull-right">Login</button>
					</div>
				</div>
			</div>
			
			<span class="clearfix"></span>	

			<div class="login-footer">
				<div class="row">
					<div class="col-xs-6 col-md-6">
						<div class="left-section">
							&nbsp;
						</div>
					</div>
					<div class="col-xs-6 col-md-6 pull-right">
					</div>
				</div>
			</div>
		  </form>
		</div>
	</div>
</body>
</html>
