<div class="col-md-12">
    <div class="widget-wrap">
        <div class="widget-container">
            <div class="widget-content">

                @if($action=='add')
                    <form id="unit_form" class="form-horizontal material-form j-forms" role="form" method="POST" id="Submit" novalidate="novalidate" action="{{ url('/unit') }}" files="true" enctype="multipart/form-data">
                @else
                    {!! Form::model($unit,array('route' => array('unit.update',$unit->id), 'id' =>'unit_form', 'method' => 'patch', 'class' => 'form-horizontal material-form j-forms')) !!}
                @endif


                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <label class="col-md-12 control-label">Units</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" value="@if(isset($unit->name)){{ $unit->name }} @endif" name="unit" placeholder="Unit Name" required="required"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-footer">
                        <div class="col-md-8">
                            <button class="btn btn-success primary-btn" type="Submit">Submit</button>
                            @if($action=='add')
                                <button class="btn btn-danger primary-btn" type="Reset">Reset</button>
                            @else
                                <a class="btn btn-danger primary-btn" href="/unit" type="Reset">Cancel</a>
                            @endif
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

            $("#unit_form").validate({
                rules: {
                    unit:"required"

                },
                messages: {
                    unit:"Unit name field can not be empty."

                }
            })
            $('#Submit').click(function() {
                $("#unit_form").validate();  // This is not working and is not validating the form
            });

        });
    </script>
@stop
