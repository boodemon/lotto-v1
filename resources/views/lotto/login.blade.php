<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Administrators Backoffice</title>
	<link rel="icon" href="{{url('/public/admin/images/icon-logo.png')}}" type="image/png">
	<link rel="shortcut icon" href="{{url('/public/admin/images/icon-logo.png')}}" type="image/png">

	<link href="{{ asset('node_modules/bulma/css/bulma.css') }}" rel="stylesheet">
	<link href="{{ asset('public/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" media="all" />
	<link href="{{ asset('/public/css/login.css') }}" rel="stylesheet">

	<!-- Fonts -->

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
<section class="hero is-fullheight is-dark is-bold">
    <div class="hero-body">
		<div class="container">
			<div class="columns is-vcentered">
				<div class="column is-4 is-offset-4">
					<form class="" role="form" method="POST" action="{{ url('/admin/login') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<h3 class="title">Administrators Login</h3>
							<label class="label">Username</label> 
							<p class="control">
								<input type="text" class="input" name="email" value="{{ old('email') }}" placeholder="Username" autofocus>
							</p>
							<label class="label">Password</label>
							<p class="control">
								<input type="password" class="input" name="password" placeholder="Password">
							</p>

							<p class="control">
								<label class="checkbox">
									<input type="checkbox" name="remember"> Remember Me
								</label>
							</p>
								<button type="submit" class="button is-success is-pulled-right"><i class="fa fa-lock"></i>&nbsp;  Login </button>
						
					</form>
				</div>
			</div>
		</div>
    </div>

	</section>
</body>
</html>
