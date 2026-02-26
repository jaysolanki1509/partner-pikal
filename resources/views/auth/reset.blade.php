
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<title>Pikal-Login</title>
	<link type="text/css" rel="stylesheet" href="/assets/css/new/font-awesome.css">
	<link type="text/css" rel="stylesheet" href="/assets/css/new/material-design-iconic-font.css">
	<link type="text/css" rel="stylesheet" href="/assets/css/new/bootstrap.css">
	<link type="text/css" rel="stylesheet" href="/assets/css/new/animate.css">
	<link type="text/css" rel="stylesheet" href="/assets/css/new/layout.css">
	<link type="text/css" rel="stylesheet" href="/assets/css/new/components.css">
	<link type="text/css" rel="stylesheet" href="/assets/css/new/widgets.css">
	<link type="text/css" rel="stylesheet" href="/assets/css/new/plugins.css">
	<link type="text/css" rel="stylesheet" href="/assets/css/new/pages.css">
	<link type="text/css" rel="stylesheet" href="/assets/css/new/bootstrap-extend.css">
	<link type="text/css" rel="stylesheet" href="/assets/css/new/common.css">
	<link type="text/css" rel="stylesheet" href="/assets/css/new/responsive.css">
	<link type="text/css" rel="stylesheet" href="/assets/css/new/custom.css">
</head>
<body class="login-page">
<!--Page Container Start Here-->
<section class="login-container boxed-login">
	<div class="container">
		<div class="col-md-4 col-md-offset-4">
			<div class="login-form-container">
				<form role="form" class="j-forms" method="POST" action="{{ url('/password/reset') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="token" value="{{ $token }}">
					<div class="login-form-header">
						<div class="logo">
							<a href="/owner" title="Admin Template"><img src="/images/fk_logo.png" alt="Profile"/></a>
						</div>
					</div>
					<div class="login-form-content">

					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>{{ trans('Auth.Whoops!') }}</strong> {{ trans('Auth.There were some problems with your input.') }}<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<!-- start login -->
						<div class="unit">
							<div class="input login-input">
								<label class="icon-left" for="email">
									<i class="zmdi zmdi-account"></i>
								</label>
								<input class="form-control login-frm-input"  type="text" id="email" name="email" value="{{ old('email') }}" placeholder="Email">
							</div>
						</div>
						<!-- end login -->

						<!-- start password -->
						<div class="unit">
							<div class="input login-input">
								<label class="icon-left" for="password">
									<i class="zmdi zmdi-key"></i>
								</label>
								<input class="form-control login-frm-input"  type="password" id="password" name="password" placeholder="Password">
							</div>
						</div>
						<!-- end password -->

						<!-- start password -->
						<div class="unit">
							<div class="input login-input">
								<label class="icon-left" for="password_confirmation">
									<i class="zmdi zmdi-key"></i>
								</label>
								<input class="form-control login-frm-input"  type="password" id="password_confirmation"  name="password_confirmation" placeholder="Confirm Password">
							</div>
						</div>
						<!-- end password -->

					</div>
					<div class="login-form-footer">
						<button type="submit" class="btn-block btn btn-primary">{{ trans('Auth.Reset Password') }}</button>
					</div>

				</form>
			</div>
		</div>
	</div>
	<!--Footer Start Here -->
	<footer class="login-page-footer">
		<div class="container">
			<div class="row">
				<div class="col-md-4 col-md-offset-4 col-sm-4 col-sm-offset-4">
					<div class="footer-content">
						<span class="footer-meta">Crafted with&nbsp;<i class="fa fa-heart"></i>&nbsp;by&nbsp;<a href="http://pikal.io">Pikal</a></span>
					</div>
				</div>
			</div>
		</div>
	</footer>
	<!--Footer End Here -->
</section>
<!--Page Container End Here-->
<script src="/assets/js/new/lib/jquery-min.js"></script>
<script src="/assets/js/new/lib/jquery-migrate-min.js"></script>
<script src="/assets/js/new/lib/bootstrap-min.js"></script>
<script src="/assets/js/new/lib/jRespond-min.js"></script>
<script src="/assets/js/new/lib/hammerjs.js"></script>
<script src="/assets/js/new/lib/jquery.hammer-min.js"></script>
<script src="/assets/js/new/lib/smoothscroll.js"></script>
<script src="/assets/js/new/lib/smart-resize-min.js"></script>
<script src="/assets/js/new/lib/jquery.validate.js"></script>
<script src="/assets/js/new/lib/jquery.form.js"></script>
<script src="/assets/js/new/lib/j-forms-min.js"></script>
<script src="/assets/js/new/lib/login-validation-min.js"></script>
</body>
</html>
