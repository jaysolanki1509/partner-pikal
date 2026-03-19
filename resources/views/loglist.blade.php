<html>
    <head>
        @include('partials.styles')

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pikal</title>
        <link rel="icon" type="image/png" href="/bower_components/images/favicon.ico" />
        <link type="text/css" rel="stylesheet" href="/bower_components/css/bootstrap.css">
        <link type="text/css" rel="stylesheet" href="/bower_components/css/style.css">
        <script type="text/javascript" src="/bower_components/js/jquery-1.11.0.min.js"></script>
        <script type="text/javascript" src="/bower_components/js/bootstrap.js"></script>



    </head>

    <body>

        <div class="full-width-container">

            <a href="/owner" style="margin:10px; float:right;" class="btn btn-primary"><i class="fa fa-dashboard"></i> Home</a>
            <a href="/pushlog" style="margin:10px; float:right;" class="btn btn-primary"><i class="fa fa-backward"></i> Fetch Log</a>

            <div class="form-group" >
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="dataTable_wrapper" style="overflow-x: auto;">
                                    <table class="table table-striped table-bordered table-hover" id="loglist">

                                        <tr>
                                            <td style="font-size: 14px;">File</td>
                                            <td style="font-size: 14px;">Outlet</td>
                                            <td style="font-size: 14px;">UserName</td>
                                            <td style="font-size: 14px;">Model</td>
                                            <td style="font-size: 14px;">Os Ver.</td>
                                            <td style="font-size: 14px;">App Ver.</td>
                                        </tr>

                                        @foreach($logdetails as $logdetail)
                                            <tr>
                                                <td style="font-size: 14px;"><a href="{{ $logdetail->path }}"><i class="fa fa-cloud-download"></i></a></td>
                                                <td style="font-size: 14px;">{{ $logdetail->name }}</td>
                                                <td style="font-size: 14px;">{{ $logdetail->user_name }}</td>
                                                <td style="font-size: 14px;">{{ $logdetail->model }}</td>
                                                <td style="font-size: 14px;">{{ $logdetail->device_os }}</td>
                                                <td style="font-size: 14px;">{{ $logdetail->app_version }}</td>
                                            </tr>
                                        @endforeach

                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </body>

    @include('partials.scripts')

    <script type="text/javascript">

        $(document).ready(function() {
            $('#loglist').DataTable({
                responsive: true,
                pageLength: 100
            });
        });

    </script>

</html>