@extends('partials.default')
<style>
    .page-header{
        display: none;
    }
</style>
@section('content')

    <!--Page Container Start Here-->
    <section class="error-container">
        <div class="container">
            <div class="error-wrap">
                <div class="error-container">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="error-code">
                                    <div>4<span>0</span>4</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="error-message">
                                    <h4>Oops! Page not found...</h4>
                                    <p>
                                        We are sorry the page you are trying to reach dose not exist :(
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@stop
@section('page-scripts')
    <script>
        $(document).ready(function() {
            $('.row:first').hide();
        });
    </script>
@stop
