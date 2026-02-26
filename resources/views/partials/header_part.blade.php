<?php use App\Outlet;
use App\OutletMapper;
use Illuminate\Support\Facades\Auth;use Illuminate\Support\Facades\Session;
?>

<header class="topbar clearfix">
    <!--Topbar Left Branding With Logo Start-->
    <div class="topbar-left pull-left">
        <div class="clearfix">
            <ul class="left-branding pull-left clickablemenu ttmenu dark-style menu-color-gradient">
                <li><span class="left-toggle-switch"><i class="zmdi zmdi-menu"></i></span></li>
                <li>
                    <div class="logo">
                        <a href="/"><img src="/images/pikl_logo.png" alt="logo"></a>
                    </div>
                </li>
            </ul>

        </div>
    </div>
    <!--Topbar Left Branding With Logo End-->
    @if (Auth::check())

        {{--<div class="col-md-3">
            <form class="material-form">
                <select id="main_filter" class="form-control">

                    @if( isset($ot_obj) && sizeof($ot_obj) > 1 )
                        --}}{{--<option value="">All</option>--}}{{--
                    @endif

                    @if( isset($ot_obj) && sizeof($ot_obj) > 0 )
                        @foreach( $ot_obj as $o_key=>$o_val )

                            <option value="{!! $o_key !!}" @if($ses_outlet == $o_key ){!! 'selected' !!} @endif>{!! $o_val !!}</option>
                        @endforeach
                    @endif

                </select>
            </form>
        </div>--}}
        <div class="col-md-2 pull-right setting-icon">
            <a href="{{ url('/logout') }}" class="pull-right" title="Logout"><i class="zmdi zmdi-power zmdi-hc-2x"></i></a>
            <a href="/changepass" class="pull-right" title="Change Password"><i class="zmdi zmdi-key zmdi-hc-2x"></i></a>
        </div>
    @endif
    <!--Topbar Right Start-->
    <div class="topbar-right pull-right">
        <div class="clearfix">
        </div>
    </div>
    <!--Topbar Right End-->
</header>
