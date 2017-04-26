<!DOCTYPE html>
<html>
	<head>
		<title>Lottor v.1</title>
	<link href="{{ asset('node_modules/bulma/css/bulma.css') }}" rel="stylesheet">
	<link href="{{ asset('public/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" media="all" />
		<link rel="stylesheet" href="{{ asset('/public/css/aside.css') }}" type="text/css"/>
	</head>
	<body>
		<nav class="nav is-dark has-shadow" id="top">
			<div class="container">
				<div class="nav-left">
					<a class="nav-item" href="{{ url('/') }}">
						<img src="{{ asset('public/images/lotto-logo.png') }}" alt="Description">
					</a>
				</div>
				<span class="nav-toggle">
					<span></span>
					<span></span>
					<span></span>
				</span>
				<div class="nav-right nav-menu is-hidden-tablet">
					<a class="nav-item is-tab is-active">
						Dashboard
					</a>
					<a class="nav-item is-tab">
						Activity
					</a>
					<a class="nav-item is-tab">
						Timeline
					</a>
					<a class="nav-item is-tab">
						Folders
					</a>
				</div>
			</div>
		</nav>
		
		<div class="columns">
			@include('lotto.layouts.inc-sidebar-' . Auth::guard('admin')->user()->type )
			<div class="content column is-10">    
				@yield('content')
			</div>
		</div>
		
		<footer class="footer">
			<div class="container">
				<div class="has-text-centered">
					<p>
						&copy;Copyright <strong>The Lottor</strong>
					</p>
				</div>
			</div>
		</footer>
		<script async="" type="text/javascript" src="{{ asset('public/js/bulma.js') }}"></script>
	</body>
</html>