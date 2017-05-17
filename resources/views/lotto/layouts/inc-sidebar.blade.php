<div class="left_col scroll-view">
	<div class="navbar nav_title" style="border: 0;">
		<a href="{{ url('/') }}" class="site_title"><img src="{{ asset('public/images/logo.png') }}" class="lotto-logo" /><span>lotto (Thailand) Co., Ltd.</span></a>
	</div>

	<div class="clearfix"></div>

	<!-- menu profile quick info -->
	<div class="profile">
		<!--
		<div class="profile_pic">
			<img src="{{ asset('public/images/logo.png') }}" alt="..." class="img-circle profile_img">
		</div>
		-->
		<div class="profile_info">
			<span>Welcome,</span>
			<h2>{{ Auth::guard('admin')->user()->name }}</h2>
		</div>
	</div>
	<!-- /menu profile quick info -->
	<br />
	<!-- sidebar menu -->
	<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
		<div class="menu_section">
			<h3>General</h3>
            <ul class="nav side-menu">
				<li><a href="{{ url('dashboard') }}" title="Home dashboard"><i class="fa fa-home"></i> Home </a></li>
				<li><a href="{{ url('result') }}" title="Order"><i class="fa fa-ticket"></i> บันทึกผลการออกสลาก </a></li>
				<li><a href="{{ url('setting') }}" title="Order"><i class="fa fa-ticket"></i> ตั้งค่าผลตอบแทน </a></li>
				<li><a href="{{ url('dealer') }}" title="lotto User"><i class="fa fa-user"></i> ตัวแทนขาย </a></li>
				<li><a href="{{ url('user') }}" title="lotto User"><i class="fa fa-user"></i> ผู้ดูแลระบบ </a></li>
            </ul>
        </div>
	</div>
	<!-- /sidebar menu -->
			
	<!-- /menu footer buttons -->
	<div class="sidebar-footer hidden-small">
		<a data-toggle="tooltip" data-placement="top" href="{{ url('logout') }}" title="Logout">
			<span class="glyphicon glyphicon-off" aria-hidden="true"></span>
		</a>
		
		<a data-toggle="tooltip" data-placement="top" id="logs" href="#" title="History log">
			<span class="fa fa-history" aria-hidden="true"></span>
		</a>
	</div>
	<!-- /menu footer buttons -->
</div>
