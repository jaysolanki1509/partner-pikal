
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>Pikal-SignUp</title>
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
        <div class="col-md-6 col-md-offset-3">
            <div class="login-form-container">
                <form role="form" class="j-forms" id="forms-login" method="POST" action="{{  url('/signup') }}">

                    <div class="login-form-header">
                        <div class="logo">
                            <a href="/owner" title="Admin Template"><img src="/images/fk_logo.png" alt="Profile"/></a>
                        </div>
                    </div>
                    <div class="login-form-content">

                        @if (count($errors) > 0)
                            <div class="alert alert-danger" style="padding-left: 0px;padding-top: 5px;padding-right: 3px;padding-bottom: 5px;">
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
                                <input class="form-control login-frm-input"  type="text" name="account" id="account" value="{{ Input::old('account') }}" placeholder="Account name">
                            </div>
                        </div>
                        <!-- end login -->

                        <!-- start login -->
                        <div class="unit">
                            <div class="input login-input">
                                <label class="icon-left" for="email">
                                    <i class="zmdi zmdi-account"></i>
                                </label>
                                <input class="form-control login-frm-input"  type="text" maxlength="15" name="user_name" id="user_name" value="{{ Input::old('user_name') }}" placeholder="Username">
                            </div>
                        </div>
                        <!-- end login -->

                        <!-- start login -->
                        <div class="unit">
                            <div class="input login-input">
                                <label class="icon-left" for="email">
                                    <i class="zmdi zmdi-email"></i>
                                </label>
                                <input class="form-control login-frm-input"  type="email" name="email" id="email" value="{{ Input::old('email') }}" placeholder="Email">
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
                                <label class="icon-left" for="password">
                                    <i class="zmdi zmdi-key"></i>
                                </label>
                                <input class="form-control login-frm-input"  type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password">
                            </div>
                        </div>
                        <!-- end password -->


                        <!-- start login -->
                        <div class="unit">
                            <div class="input login-input">
                                <label class="icon-left" for="email">
                                    <i class="zmdi zmdi-phone"></i>
                                </label>
                                <input class="form-control login-frm-input"  type="number" name="contact_no" id="contact_no" value="{{ Input::old('contact_no') }}" placeholder="Contact no">
                            </div>
                        </div>
                        <!-- end login -->

                        <!-- start login -->
                        <div class="unit">
                            <div class="input login-input">
                                <select id="gender" name="gender" class="form-control login-frm-input">
                                    <option value="">Select Gender</option>
                                    <option value="Male">{{ trans('Auth.Male') }}</option>
                                    <option value="Female">{{ trans('Auth.Female') }}</option>
                                </select>
                            </div>
                        </div>
                        <!-- end login -->

                        <!-- start login -->
                        <div class="unit">
                            <div class="input login-input">
                                <select id="state" name="state" class="form-control login-frm-input">
                                    <option value="">Select State</option>
                                    @foreach( $states as $st )
                                        <option value="{{ $st->name }}">{{ $st->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- end login -->

                        <!-- start login -->
                        <div class="unit">
                            <div class="input login-input">
                                <select id="city" name="city" class="form-control login-frm-input">
                                    <option value="">Select City</option>
                                    @foreach( $cities as $ct )
                                        <option value="{{ $ct->name }}">{{ $ct->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- end login -->

                    </div>
                    <div class="login-form-footer">
                        <button type="submit" class="btn-block btn btn-primary">Sign Up</button>
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
<script src="/assets/js/new/lib/select2.full.js"></script>
<script type="application/javascript">

    $(document).ready(function() {

        $('#gender').select2({
            placeholder: 'Select Gender'
        });

        $('#state').select2({
            placeholder: 'Select State'
        });

        $('#city').select2({
            placeholder: 'Select City'
        });

    });

</script>

</body>
</html>


