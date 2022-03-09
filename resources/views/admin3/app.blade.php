<!DOCTYPE html>
<html>
<head>
		<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>Control de Activos fijos</title>

	<!-- Site favicon -->
	<!-- <link rel="shortcut icon" href="images/favicon.ico"> -->

	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- Google Font -->
	<!-- <link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,600,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet"> -->
	<!-- CSS -->
	<link rel="stylesheet" href="{{asset('public/admin3/css/style.css')}}">

	<!-- Global site tag (gtag.js) - Google Analytics -->
	@yield('css')
	<style type="text/css">
		@yield('estilos')	
	</style>
	
</head>
<body>
		<div class="pre-loader"></div>
	<div class="header clearfix">	   
		<div class="header-right">
			<h3 style="display: inline-block;font-size: 20px;margin-top:20px;margin-left: 20px">Control de activos fijos.</h3>
			<div class="brand-logo">
				<a href="index.php">
					<img src="{{asset('public/logo-universidad-tecnologica-metropolitana.png')}}" alt="">
				</a>
			</div>
			<div class="menu-icon">
				<span></span>
				<span></span>
				<span></span>
				<span></span>
			</div>
			<div class="user-info-dropdown">
				<div class="dropdown">
					<a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
						<span class="user-icon"><i class="fa fa-user-o"></i></span>
						<span class="user-name">{{Auth::user()->nomusuario.' '.Auth::user()->nomusuario}}</span>
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<!-- <a class="dropdown-item" href="profile.php"><i class="fa fa-user-md" aria-hidden="true"></i> Profile</a>
						<a class="dropdown-item" href="profile.php"><i class="fa fa-cog" aria-hidden="true"></i> Setting</a>
						<a class="dropdown-item" href="faq.php"><i class="fa fa-question" aria-hidden="true"></i> Help</a> -->
						<a class="dropdown-item" href="{{ route('logout') }}"><i class="fa fa-sign-out" aria-hidden="true"></i> Log Out</a>
					</div>
				</div>
			</div>
			<!-- <div class="user-notification">
				<div class="dropdown">
					<a class="dropdown-toggle no-arrow" href="#" role="button" data-toggle="dropdown">
						<i class="fa fa-bell" aria-hidden="true"></i>
						<span class="badge notification-active"></span>
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<div class="notification-list mx-h-350 customscroll">
							<ul>
								<li>
									<a href="#">
										<img src="vendors/images/img.jpg" alt="">
										<h3 class="clearfix">John Doe <span>3 mins ago</span></h3>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed...</p>
									</a>
								</li>
								<li>
									<a href="#">
										<img src="vendors/images/img.jpg" alt="">
										<h3 class="clearfix">John Doe <span>3 mins ago</span></h3>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed...</p>
									</a>
								</li>
								<li>
									<a href="#">
										<img src="vendors/images/img.jpg" alt="">
										<h3 class="clearfix">John Doe <span>3 mins ago</span></h3>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed...</p>
									</a>
								</li>
								<li>
									<a href="#">
										<img src="vendors/images/img.jpg" alt="">
										<h3 class="clearfix">John Doe <span>3 mins ago</span></h3>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed...</p>
									</a>
								</li>
								<li>
									<a href="#">
										<img src="vendors/images/img.jpg" alt="">
										<h3 class="clearfix">John Doe <span>3 mins ago</span></h3>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed...</p>
									</a>
								</li>
								<li>
									<a href="#">
										<img src="vendors/images/img.jpg" alt="">
										<h3 class="clearfix">John Doe <span>3 mins ago</span></h3>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed...</p>
									</a>
								</li>
								<li>
									<a href="#">
										<img src="vendors/images/img.jpg" alt="">
										<h3 class="clearfix">John Doe <span>3 mins ago</span></h3>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed...</p>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div> -->
		</div>
	</div>		<div class="left-side-bar">
		<div class="brand-logo">
			<a href="index.php">
				<img src="vendors/images/deskapp-logo.png" alt="">
			</a>
		</div>
		<div class="menu-block customscroll">
			<div class="sidebar-menu">
				@include('admin3.menu')
			</div>
		</div>
	</div>	
	<div class="main-container">
	   <div class="customscroll customscroll-10-p height-100-p xs-pd-20-10 pd-ltr-20 mCustomScrollbar _mCS_3 mCS-autoHide" style="position: relative; overflow: visible;">
		@yield('contenido')
		</div>
	</div>
		<!-- js -->
	<script src="{{asset('public/admin3/js/script.js')}}"></script>
	@yield('scripts')	
	<script type="text/javascript">
		@yield('documentready')
	</script>
	@yield('vuejs')
</body>
</html>