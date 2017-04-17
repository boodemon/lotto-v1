<!DOCTYPE html>
<html>
	<head>
		<title>Lottor v.1</title>
		<link rel="stylesheet" href="{{ asset('/public/bulma/css/bulma.css') }}" type="text/css"/>
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
    <aside class="column is-2 aside hero is-fullheight is-hidden-mobile">
      <div>
        <div class="uploader has-text-centered">
          <a class="button">
            <i class="fa fa-upload"></i>
          </a>
        </div>
        <div class="main">
          <div class="title">Main</div>
          <a href="{{ url('#') }}" class="item active"><span class="icon"><i class="fa fa-home"></i></span><span class="name">Dashboard</span></a>
          <a href="{{ url('#') }}" class="item"><span class="icon"><i class="fa fa-map-marker"></i></span><span class="name">Activity</span></a>
          <a href="{{ url('#') }}" class="item"><span class="icon"><i class="fa fa-th-list"></i></span><span class="name">Timeline</span></a>
          <a href="{{ url('#') }}" class="item"><span class="icon"><i class="fa fa-folder-o"></i></span><span class="name">Folders</span></a>
        </div>
      </div>
    </aside>
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
        <p>
          <a class="icon" href="https://github.com/jgthms/bulma">
            <i class="fa fa-github"></i>
          </a>
        </p>
      </div>
    </div>
  </footer>
  <script async="" type="text/javascript" src="{{ asset('public/js/bulma.js') }}"></script>
	</body>
</html>