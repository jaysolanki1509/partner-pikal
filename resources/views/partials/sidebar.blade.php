<!--Leftbar Start Here-->
<?php
use App\OutletMapper;use Illuminate\Support\Facades\Auth;use Illuminate\Support\Facades\Session;

    if (!Auth::user())
    {
        return view('auth.login');
    }


    $acc_id = Auth::user()->account_id;
    $account = \App\Account::find($acc_id);

    $outlet_id = Session::get('outlet_session');
    $ot_obj = \App\Outlet::find($outlet_id);
    $table_lable = 'Table';

?>
<aside class="leftbar material-leftbar">
    <div class="left-aside-container">
        <div class="user-profile-container">
            <div class="user-profile clearfix">
                <div class="admin-user-thumb">
                    <i class="zmdi zmdi-account-circle zmdi-hc-4x"></i>
                </div>
                <div class="admin-user-info">
                    <ul>
                        <li style="color: #555555;"><label>{{ ucfirst(Auth::user()->user_name) }}</label></li>
                        <li>
                            @if (Auth::check())
                                <?php
                                    $ses_outlet = Session::get('outlet_session');

                                    $ot_obj1 = OutletMapper::getOutletsByOwnerId();
                                    unset($ot_obj1[""]);
                                    $count = 0;

                                    foreach( $ot_obj1 as $o_key=>$o_val ){

                                        if($count == 0 && $ses_outlet == '' ){
                                            $ses_outlet = $o_key;
                                        }
                                        $count++;
                                    }

                                ?>
                                <form class="material-form">
                                    <select id="main_filter" class="form-control">

                                        {{--@if( isset($ot_obj1) && sizeof($ot_obj1) > 1 )
                                            --}}{{--<option value="">All</option>--}}{{--
                                        @endif--}}

                                        @if( isset($ot_obj1) && !empty($ot_obj1))
                                            @foreach( $ot_obj1 as $o_key=>$o_val )
                                                <option value="{!! $o_key !!}" @if($ses_outlet == $o_key ){!! 'selected' !!} @endif>{!! $o_val !!}</option>
                                            @endforeach
                                        @endif

                                    </select>
                                </form>
                            @endif
                        </li>
                    </ul>
                </div>

            </div>
            {{--<div class="admin-bar">
                <ul>
                    --}}{{--<li><a href="#"><i class="zmdi zmdi-account"></i>
                        </a>
                    </li>--}}{{--
                    <li><a href="/changepass"><i class="zmdi zmdi-key"></i>
                        </a>
                    </li>
                    --}}{{--<li><a href="#"><i class="zmdi zmdi-settings"></i>
                        </a>
                    </li>--}}{{--
                    <li><a href="{{ url('/logout') }}"><i class="zmdi zmdi-power"></i>
                        </a>
                    </li>
                </ul>
            </div>--}}
        </div>
        <ul class="list-accordion">

            <li><a href="/"><i class="zmdi zmdi-view-dashboard"></i><span class="list-label">Dashboard</span></a></li>
            @if(Auth::user()->user_name == 'udupihome')
                <li><a href="/table_index"><i class="zmdi zmdi-file-text"></i><span class="list-label">Orders</span></a></li>
                <li><a href="/orderslist"><i class="zmdi zmdi-file-text"></i><span class="list-label">Orderslist</span></a></li>
            @else
                <li><a href="/orderslist"><i class="zmdi zmdi-file-text"></i><span class="list-label">Orders</span></a></li>
            @endif
           
            <li>
                <a href="#"><i class="zmdi zmdi-chart"></i><span class="list-label">Reports</span></a>
                <ul>
                    @if( isset($account) && $account->enable_cancellation_report == 1 )
                        <li><a href="/cancellationreport"><i class="zmdi zmdi-chart"></i><span class="list-label">Cancellation Report</span></a></li>
                    @endif
                    <li><a href="/summary_report"><i class="zmdi zmdi-chart"></i><span class="list-label">Summary Report</span></a></li>
                    <li><a href="/reports"><i class="zmdi zmdi-chart"></i><span class="list-label">Item Sales Report</span></a></li>
                    
                    <li><a href="/sales-report"><i class="zmdi zmdi-chart"></i><span class="list-label">Sales Report</span></a></li>
                    
                    <li><a href="/detail_discount_report"><i class="zmdi zmdi-chart"></i><span class="list-label">Discount Report</span></a></li>
                    <li><a href="/expense-report"><i class="zmdi zmdi-chart"></i><span class="list-label">Expense Report</span></a></li>
                    <li><a href="/cash-sales"><i class="zmdi zmdi-chart"></i><span class="list-label">Cash Tally Report</span></a></li>

                    {{--<li class="dropdown"><a href="/daily_report_pdf"><i class="fa fa-building fa-fw"></i>Download Daily Report</a></li>--}}
                    <li><a href="/snapshot"><i class="zmdi zmdi-chart"></i><span class="list-label">&nbsp;Snapshot</span></a></li>
                    <li><a href="/revenue-report"><i class="zmdi zmdi-chart"></i><span class="list-label">&nbsp;Revenue Report</span></a></li>
                    @if(Auth::user()->user_name == 'govind' || Auth::user()->user_name == 'toritos'  || Auth::user()->user_name == 'mfc')
                        <li><a href="/attendance-sheet"><i class="zmdi zmdi-chart"></i><span class="list-label">Attendance Sheet</span></a></li>
                    @endif

                    @if( isset($account) && $account->enable_inventory == 1 )

                        <li><a href="/purchase-rate-report"><i class="zmdi zmdi-chart"></i><span class="list-label">Purchase Rate Report</span></a></li>
                       
                        {{--<li class="dropdown"><a href="/stock-ageing-report"><i class="fa fa-building fa-fw"></i>Stock Ageing Report</a></li>--}}
                        <li><a href="/response-deviation"><i class="zmdi zmdi-chart"></i><span class="list-label">Response Deviation Report</span></a></li>
                    @endif

                    @if( isset($ot_obj) && !empty($ot_obj) && $ot_obj->zoho_config == 1 )
                        <li><a href="/zoho-unsync-orders"><i class="zmdi zmdi-chart"></i><span class="list-label">Zoho Unsync Orders</span></a></li>
                    @endif

                </ul>
            </li>
            <li><a href="/customers"><i class="fa fa-users fa-fw"></i><span class="list-label">Customers</span></a></li>

            @if(!isset(Auth::user()->created_by) || Auth::user()->created_by == '')
                <li>
                    <a href="#"><i class="zmdi zmdi-blur-linear"></i><span class="list-label">Expense</span></a>
                    <ul>
                        <li><a href="/expense/pending"><i class="zmdi zmdi-money-box"></i><span class="list-label">Expense List</span></a></li>
                        <li><a href="/expense-category-index"><i class="zmdi zmdi-tv-list"></i><span class="list-label">Expense Category</span></a></li>
                    </ul>
                </li>
            @else
                <li><a href="/expense/pending"><i class="zmdi zmdi-blur-linear"></i><span class="list-label">Expense</span></a></li>
            @endif

            {{--<li>--}}
                {{--<a href="#"><i class="zmdi zmdi-floppy"></i><span class="list-label">Booking</span></a>--}}
                {{--<ul>--}}
                    {{--<li><a href="/booking/"><i class="zmdi zmdi-money-box"></i><span class="list-label">Calender</span></a></li>--}}
                    {{--<li><a href="/booking/create"><i class="zmdi zmdi-tv-list"></i><span class="list-label">Book new</span></a></li>--}}
                    {{--<li><a href="/booking/list"><i class="zmdi zmdi-tv-list"></i><span class="list-label">Book list</span></a></li>--}}
                {{--</ul>--}}
            {{--</li>--}}

        @if( isset($account) && $account->enable_inventory == 1 )
                {{--<li><a href="/ongoing-orders"><i class="fa fa-rss fa-fw"></i><span class="list-label">Ongoing Orders</span></a></li>--}}
                <li>
                    <a href="#"><i class="zmdi zmdi-shopping-cart-plus"></i><span class="list-label">Inventory</span></a>
                    <ul>
                        @if(!isset(Auth::user()->created_by) || Auth::user()->created_by == '')
                            <li><a href="/location"><i class="zmdi zmdi-gps-dot"></i><span class="list-label">Location</span></a></li>
                        @endif
                        <li><a href="/vendor"><i class="zmdi zmdi-shopping-basket"></i><span class="list-label">Vendors</span></a></li>
                        <li><a href="/purchase"><i class="zmdi zmdi-shopping-cart"></i><span class="list-label">Purchase</span></a></li>
                        <li><a href="/stocks"><i class="zmdi zmdi-network-outline"></i><span class="list-label">Stock</span></a></li>
                        <li><a href="/requestItem/create"><i class="zmdi zmdi-flight-takeoff"></i><span class="list-label">Request</span></a></li>
                        <li><a href="/requestItemProcess"><i class="zmdi zmdi-flight-land"></i><span class="list-label">Process Request</span></a></li>
                        <li><a href="/prepareRecipe"><i class="zmdi zmdi-repeat"></i><span class="list-label">Prepare Recipe</span></a></li>
                        <li><a href="/stock-request-report"><i class="zmdi zmdi-chart"></i><span class="list-label">Stock Request Report</span></a></li>
                        <li><a href="/stock-response-report"><i class="zmdi zmdi-chart"></i><span class="list-label">Stock Response Report</span></a></li>
                        <li><a href="/closing-stock-report"><i class="zmdi zmdi-chart"></i><span class="list-label">Closing Stock Report</span></a></li>
                        <li><a href="/sales-consumption-report"><i class="zmdi zmdi-chart"></i><span class="list-label">Sales & Consumption Report</span></a></li>
                    </ul>
                </li>

            @endif

            @if(Auth::user()->user_name == 'govind' || Auth::user()->user_name == 'toritos' || Auth::user()->user_name == 'mfc' || Auth::user()->user_name == 'pikldemo' || Auth::user()->user_name == 'TeaPoint' || Auth::user()->user_name == 'TeaPoint-12')
                <li><a href="/attendance"><i class="zmdi zmdi-format-list-bulleted"></i><span class="list-label">Attendance</span></a></li>
                <li><a href="/staffs"><i class="zmdi zmdi-accounts-alt"></i><span class="list-label">Staffs</span></a></li>
                <li><a href="/staffing"><i class="zmdi zmdi-account-box-phone"></i>
                        <span class="list-label">Staffing</span></a></li>

            @endif

            @if(Auth::user()->user_name == 'govind' || Auth::user()->user_name == 'toritos')

                <li>
                    <a href="#"><i class="zmdi zmdi-shopping-cart-plus"></i><span class="list-label">Reward Points</span></a>
                    <ul>
                        <li><a href="/reward-points"><i class="zmdi zmdi-shopping-basket"></i><span class="list-label">Debit / Credit</span></a></li>
                    </ul>
                </li>

            @endif

            @if(Auth::user()->user_name == 'govind' || Auth::user()->user_name == 'toritos'  || Auth::user()->user_name == 'mfc' || Auth::user()->user_name == 'pikldemo' || Auth::user()->user_name == 'TeaPoint' || Auth::user()->user_name == 'TeaPoint-12')

                <li>
                    <a href="#"><i class="fa fa-list-ol" aria-hidden="true"></i><span class="list-label">Manage Staff</span></a>
                    <ul>
                        <li><a href="/staff-roles"><i class="zmdi zmdi-accounts-outline"></i><span class="list-label">Staff Roles</span></a></li>
                        <li><a href="/staff-shifts"><i class="zmdi zmdi-time-interval"></i><span class="list-label">Staff Shifts</span></a></li>
                    </ul>
                </li>
            @endif
            @if(Auth::user()->user_name == 'govind' || Auth::user()->user_name == 'toritos'  || Auth::user()->user_name == 'mfc')
                <li>
                    <a href="#"><i class="zmdi zmdi-accounts-list-alt"></i><span class="list-label">Masters</span></a>
                    <ul>
                        <li><a href="/admin-outlet"><i class="zmdi zmdi-money"></i><span class="list-label">Outlet Config</span></a></li>
                        <li><a href="/campaign-report"><i class="fa fa-list-alt" aria-hidden="true"></i><span class="list-label">Campaign Report</span></a></li>
                        <li><a href="/account-setting"><i class="zmdi zmdi-settings"></i><span class="list-label">Account Settings</span></a></li>
                        <li><a href="/source"><<i class="zmdi zmdi-cloud-outline"></i><span class="list-label">Sources</span></a></li>
                        <li><a href="/settings/add"><i class="fa fa-cogs" aria-hidden="true"></i><span class="list-label">App Settings</span></a></li>
                        <li><a href="/unit"><i class="zmdi zmdi-sort-amount-asc"></i><span class="list-label">Unit Master</span></a></li>
                        <li><a href="/payment-options"><i class="zmdi zmdi-balance-wallet"></i><span class="list-label">Payment Options</span></a></li>
                        <li><a href="/kot-vs-orders-diff"><i class="zmdi zmdi-chart"></i><span class="list-label">KOT vs Orders</span></a></li>
                        {{--<li class="dropdown"><a href="/shifts"><i class="fa fa-building fa-fw"></i>Shift Master</a></li>--}}
                        <li><a href="/generate-summary-report"><i class="fa fa-copyright" aria-hidden="true"></i><span class="list-label">Generate Summary Report</span></a></li>
                        <li><a href="/duplicate-invoiceno-report"><i class="fa fa-copyright" aria-hidden="true"></i><span class="list-label">Duplicate Invoice No. Report</span></a></li>
                        <li><a href="/payment-report"><i class="zmdi zmdi-money"></i><span class="list-label">Payment Report</span></a></li>
                        <li><a href="/termsandcondition"><i class="fa fa-copyright" aria-hidden="true"></i><span class="list-label">Terms And Conditions</span></a></li>
                        <li><a href="/owner-app-version"><i class="fa fa-copyright" aria-hidden="true"></i><span class="list-label">Owner App Version</span></a></li>
                        {{--<li><a href="/auto-process-outlet"><i class="fa fa-file-text" aria-hidden="true"></i><span class="list-label">Auto Process Outlet</span></a></li>--}}
                    </ul>
                </li>



            @endif

            <li><a href="/outlet"><i class="zmdi zmdi-home"></i><span class="list-label">Outlets</span></a></li>
            <li>
                <a href="#"><i class="zmdi zmdi-menu"></i><span class="list-label">Item Master</span></a>
                <ul>
                    <li><a href="/menutitle"><i class="zmdi zmdi-format-subject"></i><span class="list-label">Categories</span></a></li>
                    <li><a href="/menu"><i class="zmdi zmdi-format-subject"></i><span class="list-label">Items</span></a></li>
                    <li><a href="/item-option-groups"><i class="zmdi zmdi-format-subject"></i><span class="list-label">Item Option Groups</span></a></li>
                    <li><a href="/item-attributes"><i class="zmdi zmdi-format-subject"></i><span class="list-label">Item Attributes</span></a></li>
                </ul>
            </li>

            <li><a href="/users"><i class="fa fa-users fa-fw"></i><span class="list-label">Users</span></a></li>

            @if ( isset($ot_obj) && !empty($ot_obj) )

                @if( isset($ot_obj->order_lable) && $ot_obj->order_lable != '' )
                    <?php $table_lable = $ot_obj->order_lable;?>
                @endif

            @endif

            <li><a href="/tables"><i class="zmdi zmdi-border-outer"></i><span class="list-label">{{ $table_lable }}</span></a></li>
            <li><a href="/table-levels"><i class="zmdi zmdi-view-list"></i><span class="list-label">{{ $table_lable }} Levels</span></a></li>
            <li><a href="/order-place-types"><i class="zmdi zmdi-airline-seat-recline-normal"></i><span class="list-label">Order Place Types</span></a></li>
            <li><a href="/taxes"><i class="zmdi zmdi-border-outer"></i><span class="list-label">Taxes</span></a></li>
            <li><a href="/printers"><i class="fa fa-print fa-fw"></i><span class="list-label">Printers</span></a></li>
            <li><a href="/bill-template"><i class="zmdi zmdi-file-text"></i><span class="list-label">Bill Template</span></a></li>
            <li><a href="/cancellationreason"><i class="zmdi zmdi-smartphone-erase"></i><span class="list-label">Cancellation Reason</span></a></li>
            @if( isset($account) && $account->enable_feedback == 1 )
                <li><a href="/feedback-question"><i class="zmdi zmdi-file"></i><span class="list-label">Feedback Questions</span></a></li>
            @endif
            <li><a href="/designFeedBack"><i class="zmdi zmdi-file"></i><span class="list-label">Feedback Design</span></a></li>
            <li><a href="/coupongenerator"><i class="zmdi zmdi-wallpaper"></i><span class="list-label">{{ trans('Coupon.Coupons') }}</span></a></li>
            @if( isset($account) && $account->enable_inventory == 1 )
                <li><a href="/banks"><i class="fa fa-university" aria-hidden="true"></i><span class="list-label">Bank Details</span></a></li>
            @endif
        </ul>
    </div>
</aside>

