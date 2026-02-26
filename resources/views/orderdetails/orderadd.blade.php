
        <div><img src="loader.gif" class="ajax-loadernew" id="loadersearch"></div>
        <div class="col-md-12">
            <div class="col-md-3 form">
                <label class="control-label">{{ trans('OrderAdd.Order Id') }}</label>

            </div>
            <div class="col-md-3 form">
                <input id="searchorderid" type="search" class="form-control" name="searchorderid" placeholder="Search by order id">
            </div>
            <div class="col-md-6 " >
                <div class="col-md-3 form ">
                    <div class="col-md-1">
                        <label class="orderdate">{{ trans('OrderAdd.Date') }}</label>
                    </div>
                    <div class="col-md-3 searchdate">
                        <input type="text" value ="" id="datepickernew"  />
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-primary cleardate">{{ trans('OrderAdd.Clear') }}</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-12">
            <div class="col-md-3 form">
                <label class="control-label">{{ trans('OrderAdd.Status') }}</label>
            </div>
            <div class="col-md-1 form">
                <div class="btn-group" id="showorder">
                  <!--  <button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle orderst" >{{ trans('OrderAdd.All') }}<span class="caret"></span></button> -->
                    <button type="button" data-toggle="dropdown">All<span class="caret"></span></button>
                    <ul class="dropdown-menu chngstas" >
                        <li><a href="#">All</a></li>
                        <li><a href="#">Received</a></li>
                        <li><a href="#">Preparing</a></li>
                        <li><a href="#">Prepared</a></li>
                        <li><a href="#">Delivered</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-3 form">
                <label class="control-label">{{ trans('OrderAdd.Order Type') }}</label>
            </div>
            <div class="col-md-1 form">
                <div class="btn-group" id="showordertype">
                   <!-- <button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle ordertp" >{{ trans('OrderAdd.All') }}<span class="caret"></span></button> -->
                    <button type="button" data-toggle="dropdown">All<span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><a href="#">All</a></li>
                        <li><a href="#">Take Away</a></li>
                        <li><a href="#">Home Delivery</a></li>
                        <li><a href="#">Dine In</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-12 orderdisplay">
            <div class="col-md-3 form">
                <label class="control-label">{{ trans('OrderAdd.Name') }}</label>

            </div>
            <div class="col-md-3 form">
                <input id="searchname" type="search" class="form-control" name="searchname"  placeholder="Search by Name">
            </div>
        </div>
        <div class="col-md-12 orderdisplay addr" >
            <div class="col-md-3 form">
                <label class="control-label">{{ trans('OrderAdd.Address') }}</label>

            </div>
            <div class="col-md-3 form">

                <input id="searchaddr" type="search" class="form-control" name="searchaddr"  placeholder="Search by Address">
            </div>
        </div>
        <div class="col-md-12 orderdisplay">
            <div class="col-md-3 form">
                <label class="control-label">{{ trans('OrderAdd.Mobile') }}</label>
            </div>
            <div class="col-md-3 form">
                <input id="searchphnnumber" type="search" class="form-control" name="searchphnnumber"  placeholder="Search by Phone Number">
            </div>
        </div>

        <div class="col-md-12 orderdisplay ortable">
            <div class="col-md-3 form">
                <label class="control-label"></label>

            </div>
            <div class="col-md-3 form">
                {{ trans('OrderAdd.OR') }}
            </div>
        </div>

        <div class="col-md-12 orderdisplay table">
            <div class="col-md-3 form">
                <label class="control-label">{{ trans('OrderAdd.Table No') }}</label>
            </div>
            <div class="col-md-3 form">
                <input id="searchtblnumber" type="search" class="form-control" name="searchtblnumber"  placeholder="Search by Table Number">
            </div>
        </div>



