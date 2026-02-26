<?php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;
?>
<!DOCTYPE html>
<html lang="en">

<head>

    @include('partials.meta')
    <title>Pikal</title>
    @include('partials.styles_new')
    @yield('page-styles')

</head>

<body class="leftbar-view">

@if( $_SERVER['HTTP_USER_AGENT'] != 'android_app')
    @include('partials.header_part')
    @include('partials.sidebar')
@endif

    <section class="main-container">

        <div class="container-fluid">

            @if( $_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == '/home' || $_SERVER['HTTP_USER_AGENT'] == 'android_app')
            @else
                @include('partials.pageheader')
            @endif

            @yield('content')

        </div>
        @if( $_SERVER['HTTP_USER_AGENT'] != 'android_app')
            @include('partials.footer')
        @endif

    </section>

    @include('partials.scripts_new')
    @yield('page-scripts')

    <!-- jQuery -->
    <script type="text/javascript" >

        $(document).ready(function()
        {

            $('#main_filter').change(function() {
                var ol_id = $('#main_filter').val();

                $.ajax({
                    url:'/set-outlet-session',
                    type:'POST',
                    data:'outlet_id='+ol_id,
                    success:function(data){
                        console.log(data);
                        location.reload();
                    }
                })

            });

            //Add active to sidebar selected tab
            var url =  window.location.pathname;
            var remain_url = "/"+url.split("/")[1];
            console.log(remain_url);
            $(".list-accordion").find('li a').each(function (index) {
                var link_name = $(this).attr('href');
                console.log(link_name);
                if(link_name == remain_url){
                    $(this).addClass("actives");
                    $(this).parents(".acc-parent-li").find('.acc-parent').addClass('actives');
                    $(this).parents(".acc-parent-li").find('ul').css({'display':'block'});

//                    $(is_sub_menu).child('acc-parent').addClass('active');
//                    console.log(is_sub_menu);
                }
            })


        });


        function getOrderType(ord) {
            var orderarray = {};
            orderarray['dine_in']="Dine-In";

            return orderarray[ord];

        }
        function getnotificationdata(b,c){
            return;

            $.ajax({
                url: '/ajax/getordernotification',
                data: "status=" + c + "&user_id=" +b,
                success: function (data) {
                    //console.log(data.length);
                    if(data.count==0){
                        $("#notification_count").fadeOut("slow");
                    }else{
                        $("#notification_count").fadeIn("Fast");
                        $("#notification_count").html(data.length);
                        if(data.length>0){

                            for(var i=0;i<=data.length;i++){
                                //if(data[i]!='undefined' && data[i]!=''){
                                var order_id=data[i]['order_id'];
                                var outlet_id=data[i]['outlet_id'];
                                var order_type=data[i]['order_type'];
                                $(".my-notification").html("<div onclick=\"window.location='{{ url("/") }}'\"><span>New Order</span><span class='order-no'>Order No - "+order_id+" ( "+getOrderType(order_type)+" )</span></div>");
                                i++;
                                //  }
                            }
                        }
                    }
                }

            });
            myDate = (new Date());
            lastTime = myDate.getHours() + ':' + myDate.getMinutes() + ':'
                + myDate.setSeconds(myDate.getSeconds()-10000);

        }

        function getcurrentorders(){
            // console.log();
            var order_id= $("#getcurrentorders").data('order');
            var rest_id=$("#getcurrentorders").data('outlet');
            $.ajax({
                url: 'ajax/currentorderdetails',
                data: "order_id=" + order_id + "&outlet_id=" +rest_id,
                success: function (data) {
                    $("#wrapper").html(data);

                }
            });
        }

        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-387525-15', 'auto');
    ga('send', 'pageview');

    </script>

</body>

</html>