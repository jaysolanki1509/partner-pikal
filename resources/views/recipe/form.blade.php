
@if($action=='add')

    <form class="form-horizontal" role="form" method="POST" id="Submit" novalidate="novalidate" action="{{ url('/recipe') }}" files="true" enctype="multipart/form-data">
        {{--{!! Form::open(['route' =>'status.store', 'method' => 'patch', 'class' => 'autoValidate', 'files'=> true]) !!}--}}
        @else
            {{--<form class="form-horizontal" role="form" method="PUT" action="{{ url('/status/'.$Outlet->id) }}">--}}
            {!! Form::model($recipe,array('route' => array('recipe.update',$recipe->id), 'method' => 'patch', 'class' => 'autoValidate')) !!}
        @endif

        <div class="col-md-12">
            @if($action=='add')
                @if($totalOutletcount>1)
                    <div class="col-md-3 form">
                        <label class="rest">{{ trans('OrderIndex.Outlet') }}</label>
                    </div>

                    <div class="col-md-3 form">
                        <select id="rest_id" class="form-control" name="Outlet_name">
                            <option value="select" selected>{{ trans('OrderIndex.Select') }}</option>
                            @foreach($Outlet as $rest)
                                <option value="{{$rest->id or ''}}">{{$rest->name or ''}}</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    @foreach($Outlet as $rest)
                        <div class="col-md-3 form">
                            <input type="hidden" value="{{$rest->id or ''}}" id="Outlet_name" name="Outlet_name"/>
                            <label>{{$rest->name or ''}}</label>
                        </div>
                    @endforeach
                @endif
            @else
                @if($totalOutletcount>1)
                    <div class="col-md-3 form">
                        <label class="rest">{{ trans('OrderIndex.Outlet') }}</label>
                    </div>

                    <div class="col-md-3 form">
                        <select id="rest_id" class="form-control" name="Outlet_name">
                            <option value="select" selected>{{ trans('OrderIndex.Select') }}</option>
                            @foreach($Outlet as $rest)
                                @if($rest->id == $recipe->outlet_id)
                                    <option value="{{$rest->id or ''}}" selected>{{$rest->name or ''}}</option>
                                @else
                                    <option value="{{$rest->id or ''}}">{{$rest->name or ''}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                @else
                    @foreach($Outlet as $rest)
                        <div class="col-md-3 form">
                            <input type="hidden" value="{{$rest->id or ''}}" id="Outlet_name" name="Outlet_name"/>
                            <label>{{$rest->name or ''}}</label>
                        </div>
                    @endforeach
                @endif
            @endif
        </div>
        <div class="col-md-12">
            <div class="col-md-3 form">
                <label class="control-label">Title</label>
            </div>


                @if($action=='add')
                <div class="col-md-3 form">
                    <input type="text" name="title" value=""/>
                </div>
                @endif

                @if($action=='edit')
                <div class="col-md-3 form">
                    <input type="text" name="title" value="@if(isset($recipe->title)){{$recipe->title}}@endif"/>
                </div>
                @endif

        </div>

        <div class="col-md-12">
            <div class="col-md-3 form">
                <label class="control-label">Ingerdiants</label>
            </div>


            @if($action=='add')
                <div class="col-md-3 form">
                    <textarea name="recipeingrediants" rows="4" cols="50"></textarea>
                </div>
            @endif

            @if($action=='edit')
                <div class="col-md-3 form">
                    <textarea name="recipeingrediants" rows="4" cols="50">@if(isset($recipe->ingrediants)){{$recipe->ingrediants}}@endif</textarea>
                </div>
            @endif

        </div>
        <div class="col-md-12">
            <div class="col-md-3 form">
                <label class="control-label">Recipe</label>
            </div>


            @if($action=='add')
                <div class="col-md-3 form">
                    <textarea name="recipe" rows="4" cols="50"></textarea>
                </div>
            @endif

            @if($action=='edit')
                <div class="col-md-3 form">
                    <textarea name="recipe" rows="4" cols="50">@if(isset($recipe->recipes)){{$recipe->recipes}}@endif</textarea>
                </div>
            @endif

        </div>

        <div class="col-md-12">
            <div class="col-md-3 form">
                <label class="control-label">Shop Url</label>
            </div>


            @if($action=='add')
                <div class="col-md-3 form">
                    <input type="text" name="shop_url" value=""/>
                </div>
            @endif

            @if($action=='edit')
                <div class="col-md-3 form">
                    <input type="text" name="shop_url" value="@if(isset($recipe->shop_url)){{$recipe->shop_url}}@endif"/>
                </div>
            @endif

        </div>

        <div class="col-md-12">
            <div class="col-md-3 form">
                <label class="control-label">Ingrediants Url</label>
            </div>


            @if($action=='add')
                <div class="col-md-3 form">
                    <input type="text" name="ingrediants_url" value=""/>
                </div>
            @endif

            @if($action=='edit')
                <div class="col-md-3 form">
                    <input type="text" name="ingrediants_url" value="@if(isset($recipe->ingrediants_url)){{$recipe->ingrediants_url}}@endif"/>
                </div>
            @endif

        </div>


        <div class="col-md-12 form">
            <div class="col-md-3 form"></div>
            <div class="col-md-9 form">
                <button class="btn btn-primary mr5" type="Submit">Submit</button>
                <button class="btn btn-default" type="Reset">Reset</button>
            </div>
        </div>

    </form>


@section('page-scripts')

    <script type="text/javascript">
        $(document).ready(function()
        {
            $("#Submit").validate({
                rules: {
                    "title": {
                        required: true
                    },
                    "recipe": {
                        required: true
                    },
                    "shop_url": {
                        required: true
                    },
                    "ingrediants_url": {
                        required: true
                    }

                },
                messages: {
                    "title": {
                        required: "Title Must be Added"
                    },
                    "recipe": {
                        required: "Recipe Must be Added"
                    },
                    "shop_url": {
                        required: "Shop Url Must be Added"
                    },
                    "ingrediants_url": {
                        required: "Ingrediant Url Must be Added"
                    }
                }
            })
        })
        $('#Submit').click(function() {
            $("#Outlet_form").valid();  // This is not working and is not validating the form

        })
    </script>
@stop

