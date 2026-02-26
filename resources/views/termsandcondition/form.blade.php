<div class="col-md-12">
    <div class="widget-wrap">
        <div class="widget-container">
            <div class="widget-content">

                @if($action=='add')
                    <form id="terms_form" class="form-horizontal material-form j-forms" role="form" method="POST" id="Submit" novalidate="novalidate" action="{{ url('/termsandcondition') }}" files="true" enctype="multipart/form-data">
                @else
                    {!! Form::model($terms,array('route' => array('termsandcondition.update',$terms->id),'id'=>'terms_form', 'method' => 'patch', 'class' => 'form-horizontal material-form j-forms')) !!}
                @endif

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <label class="col-md-12 control-label">Terms And Conditions</label>
                                <div class="col-md-12">
                                    <textarea class="form-control" name="terms" rows="4" cols="50" placeholder="Terms And Conditions"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-footer">
                        <div class="col-md-8">
                            <button class="btn btn-success primary-btn" type="Submit">{{ trans('TermsandConditions.Submit') }}</button>
                            <button class="btn btn-danger primary-btn" type="Reset">{{ trans('TermsandConditions.Reset') }}</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@section('page-scripts')
    <script src="/assets/js/new/lib/jquery.validate.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){

            @if(Session::has('success'))
                successErrorMessage('{{ Session::get('success') }}','success');
            @endif
            @if(Session::has('error'))
                successErrorMessage('{{ Session::get('error') }}','error');
            @endif

            $("#terms_form").validate({
                rules: {
                    terms:"required"
                },
                messages: {
                    terms:"Terms and Conditions field can not be empty."
                }
            })
            $('#Submit').click(function() {
                $("#terms_form").validate();  // This is not working and is not validating the form
            });

        });
    </script>
@stop
