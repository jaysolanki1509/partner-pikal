<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/', 'HomeController@index');
Route::get('home', 'HomeController@index');
Route::get('splash','WelcomeController@index');

Route::get('/signup', array('as' => 'signup', 'uses' => 'Auth\AuthController@getRegister'));
Route::post('/signup', array('as' => 'signup', 'uses' => 'Auth\AuthController@postRegister'));

Route::get('/owner', array('as' => 'login', 'uses' => 'Auth\AuthController@getLogin'));
Route::post('/owner', array('as' => 'login', 'uses' => 'Auth\AuthController@postLogin'));
Route::get('/unsubscrib', array('as'=>'unsubscribMail','uses'=>'Auth\AuthController@unsubscribMail'));

Route::get('/user/edit/{id}', array('as' => 'edit', 'uses' => 'HomeController@editUser'));
Route::get('/checkOrderReceive', array('as' => 'edit', 'uses' => 'UserController@checkOrderReceive'));
Route::post('/user/updateUser/{id}', array('as' => 'update', 'uses' => 'HomeController@updateUser'));

Route::group(['prefix' => 'api/v1'], function () {
    Route::post('/addlike', array('as' => 'addlike', 'uses' => 'Api\v1\ApiController@addlike'));
    Route::post('/addreviews', array('as' => 'addreviews', 'uses' => 'Api\v1\ApiController@addreviews'));
    Route::post('/getreviews', array('as' => 'getreviews', 'uses' => 'Api\v1\ApiController@getreviews'));
    Route::post('/addaddress', array('as' => 'addaddress', 'uses' => 'Api\v1\ApiController@addaddress'));
    Route::get('/addaddress', array('as' => 'addaddress', 'uses' => 'Api\v1\ApiController@addaddress'));
    Route::get('/getallcities', array('as' => 'getallcities', 'uses' => 'Api\v1\ApiController@getallcities'));

    Route::get('/getlocalitybycity', array('as' => 'getlocalitybycity', 'uses' => 'Api\v1\ApiController@getlocalitybycity'));
    Route::post('/getlocalitybycity', array('as' => 'getlocalitybycity', 'uses' => 'Api\v1\ApiController@getlocalitybycity'));
    Route::post('/payuview', array('as' => 'payuview', 'uses' => 'Api\v1\ApiController@payuview'));
    Route::get('/payusuccess', array('as' => 'payusuccess', 'uses' => 'Api\v1\ApiController@payusuccess'));
    Route::post('/payusuccess', array('as' => 'payusuccess', 'uses' => 'Api\v1\ApiController@payusuccess'));
    Route::get('/payufailure', array('as' => 'payufailure', 'uses' => 'Api\v1\ApiController@payufailure'));
    Route::post('/payufailure', array('as' => 'payufailure', 'uses' => 'Api\v1\ApiController@payufailure'));
    Route::get('/addstatusforallrestaurant', array('as' => 'addstatusforallrestaurant', 'uses' => 'Api\v1\ApiController@addstatusforallrestaurant'));
    Route::get('/logincustomers',array('as' => 'logincustomers', 'uses' => 'Api\v1\ApiController@logincustomers'));
    Route::post('/logincustomers',array('as' => 'logincustomers', 'uses' => 'Api\v1\ApiController@logincustomers'));
    Route::get('/getlocality',array('as' => 'getlocality', 'uses' => 'Api\v1\ApiController@getlocality'));
    Route::post('/getlocality',array('as' => 'getlocality', 'uses' => 'Api\v1\ApiController@getlocality'));
    Route::resource('/restaurant', 'Api\v1\ApiController');

    Route::get('/restaurantmenu','Api\v1\ApiController@outletmenu');
    Route::get('/restaurantinformation','Api\v1\ApiController@outletinformation');

    Route::get('/payuview','Api\v1\ApiController@payuview');

    Route::post('/orderdetails','Api\v1\ApiController@orderdetails');
        Route::post('/orderdetails','Api\v1\ApiController@orderdetails');
        Route::post('/addcustomer','Api\v1\ApiController@addcustomer');
        Route::post('/verifyotp','Api\v1\ApiController@verifyotp');
        Route::post('/login','Api\v1\ApiController@login');
        Route::get('/updatedetail','Api\v1\ApiController@updatedetail');
        Route::post('/updatedetail','Api\v1\ApiController@updatedetail');
        Route::post('/resendotp','Api\v1\ApiController@resendotp');
        Route::post('/sendmail','Api\v1\ApiController@sendmail');
        Route::post('/forgotpassword','Api\v1\ApiController@forgotpassword');
        Route::post('/addresschange','Api\v1\ApiController@addresschange');
        Route::post('/updatepassword','Api\v1\ApiController@updatepassword');
        Route::get('/matchcouponcode','Api\v1\ApiController@matchcouponcode');
        Route::post('/matchcouponcode','Api\v1\ApiController@matchcouponcode');

        Route::post('/ownerfetchdata','Api\v1\ApiController@ownerfetchdata');
        Route::get('/ownerfetchdata','Api\v1\ApiController@ownerfetchdata');
        Route::post('/ownerlogin','Api\v1\ApiController@ownerlogin');
        Route::get('/ownerlogin','Api\v1\ApiController@ownerlogin');
        Route::post('/ownerlogout','Api\v1\ApiController@ownerlogout');
        Route::get('/ownerlogout','Api\v1\ApiController@ownerlogout');
        Route::post('/nextstatus','Api\v1\ApiController@nextstatus');
        Route::get('/nextstatus','Api\v1\ApiController@nextstatus');
        Route::get('/autoorders','Api\v1\ApiController@autoorders');
        Route::post('/autoorders','Api\v1\ApiController@autoorders');
        Route::post('/firstorder','Api\v1\ApiController@firstorder');
        Route::get('/firstorder','Api\v1\ApiController@firstorder');
        Route::get('/termsandcondition','Api\v1\ApiController@termsandcondition');
    Route::post('/ownernotification','Api\v1\ApiController@ownernotification');
    Route::get('/ownernotification','Api\v1\ApiController@ownernotification');
    Route::post('/resetorderid','Api\v1\ApiController@resetorderid');
    Route::get('/resetorderid','Api\v1\ApiController@resetorderid');
    Route::post('/generatereport','Api\v1\ApiController@generatereport');
    Route::get('/generatereport','Api\v1\ApiController@generatereport');
    Route::post('/updatemenuitem','Api\v1\ApiController@updatemenuitem');
    Route::get('/updatemenuitem','Api\v1\ApiController@updatemenuitem');
    Route::get('/cancellationreason','Api\v1\ApiController@cancellationreason');
    Route::post('/cancellationreason','Api\v1\ApiController@cancellationreason');
    Route::get('/ordercancellation','Api\v1\ApiController@ordercancellation');
    Route::post('/ordercancellation','Api\v1\ApiController@ordercancellation');
    Route::post('/owneroutlet','Api\v1\ApiController@owneroutlet');
     Route::post('/printsummary','Api\v1\ApiController@printsummary');
    Route::post('/addrecipe','Api\v1\ApiController@addrecipes');
    Route::post('/getrecipe','Api\v1\ApiController@getrecipes');

    Route::post('/pastorders','Api\v1\ApiController@pastorders');
    Route::post('/syncorderadd','Api\v1\ApiController@syncorderadd');
    Route::post('/closeCounter','Api\v1\ApiController@closeCounter');
    Route::get('/expenseApp','ExpenseController@index');
});

Route::post('outlet/addlocation/{id}','OutletController@addlocation');

Route::get('/logout', array('as' => 'logout', 'uses' => 'Auth\AuthController@getLogout'));

Route::get('/changepass', array('as' => 'changepass', 'uses' => 'UserController@changepass'));
Route::post('/passwordchange', array('as' => 'passwordchange', 'uses' => 'UserController@passwordchange'));

Route::get('autocomplete', array('as'=>'rest.autocomplete','uses'=>'RestController@autocomplete'));
Route::post('/outlet/exportexcel','OutletController@exportexcel');
Route::post('/outlet/exportdetailexcel','OutletController@exportdetailexcel');
Route::post('/update-payment-mode','OutletController@updatePaymentMode');
Route::post('/update-payment-id','OutletController@updatePaymentId');
Route::get('/taxes','OutletController@getTaxes');
Route::post('/update-taxes','OutletController@updateTaxes');
Route::post('/update-order_type-taxes','OutletController@updateOrderTypeTaxes');
Route::post('/store-delivery-charge','OutletController@storeDeliveryCharge');
Route::post("/storelayout","OutletController@updateAppLayout");
Route::post('/store-tax-details','OutletController@storeTaxDetail');


Route::resource('termsandcondition', 'TermsandconditionController');

// Route::controllers([
//     'password' => 'Auth\PasswordController',
// ]);
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

Route::get('/avtar/{id}/{size}', 'Api\v1\ApiController@getImage');
Route::get('/gallery/{id}/{size}','Api\v1\ApiController@getGallery');


// API V2 //

Route::group(['prefix' => 'api/v2'], function () {
    Route::post('/addlike', array('as' => 'addlike', 'uses' => 'Api\v2\ApiControllerV2@addlike'));
    Route::post('/addreviews', array('as' => 'addreviews', 'uses' => 'Api\v2\ApiControllerV2@addreviews'));
    Route::post('/getreviews', array('as' => 'getreviews', 'uses' => 'Api\v2\ApiControllerV2@getreviews'));
    Route::post('/addaddress', array('as' => 'addaddress', 'uses' => 'Api\v2\ApiControllerV2@addaddress'));
    Route::get('/addaddress', array('as' => 'addaddress', 'uses' => 'Api\v2\ApiControllerV2@addaddress'));
    Route::get('/getallcities', array('as' => 'getallcities', 'uses' => 'Api\v2\ApiControllerV2@getallcities'));

    Route::get('/getlocalitybycity', array('as' => 'getlocalitybycity', 'uses' => 'Api\v2\ApiControllerV2@getlocalitybycity'));
    Route::post('/getlocalitybycity', array('as' => 'getlocalitybycity', 'uses' => 'Api\v2\ApiControllerV2@getlocalitybycity'));
    Route::post('/payuview', array('as' => 'payuview', 'uses' => 'Api\v2\ApiControllerV2@payuview'));
    Route::get('/payusuccess', array('as' => 'payusuccess', 'uses' => 'Api\v2\ApiControllerV2@payusuccess'));
    Route::post('/payusuccess', array('as' => 'payusuccess', 'uses' => 'Api\v2\ApiControllerV2@payusuccess'));
    Route::get('/payufailure', array('as' => 'payufailure', 'uses' => 'Api\v2\ApiControllerV2@payufailure'));
    Route::post('/payufailure', array('as' => 'payufailure', 'uses' => 'Api\v2\ApiControllerV2@payufailure'));
    Route::get('/addstatusforallrestaurant', array('as' => 'addstatusforallrestaurant', 'uses' => 'Api\v2\ApiControllerV2@addstatusforallrestaurant'));
    Route::get('/logincustomers',array('as' => 'logincustomers', 'uses' => 'Api\v2\ApiControllerV2@logincustomers'));
    Route::post('/logincustomers',array('as' => 'logincustomers', 'uses' => 'Api\v2\ApiControllerV2@logincustomers'));
    Route::get('/getlocality',array('as' => 'getlocality', 'uses' => 'Api\v2\ApiControllerV2@getlocality'));
    Route::post('/getlocality',array('as' => 'getlocality', 'uses' => 'Api\v2\ApiControllerV2@getlocality'));
    Route::resource('/restaurant', 'Api\v2\ApiControllerV2');

    Route::get('/restaurantmenu','Api\v2\ApiControllerV2@outletmenu');
    Route::get('/restaurantinformation','Api\v2\ApiControllerV2@outletinformation');

    Route::get('/payuview','Api\v2\ApiControllerV2@payuview');

    Route::post('/orderdetails','Api\v2\ApiControllerV2@orderdetails');
    Route::post('/orderdetails','Api\v2\ApiControllerV2@orderdetails');
    Route::post('/addcustomer','Api\v2\ApiControllerV2@addcustomer');
    Route::post('/verifyotp','Api\v2\ApiControllerV2@verifyotp');
    Route::post('/login','Api\v2\ApiControllerV2@login');
    Route::get('/updatedetail','Api\v2\ApiControllerV2@updatedetail');
    Route::post('/updatedetail','Api\v2\ApiControllerV2@updatedetail');
    Route::post('/resendotp','Api\v2\ApiControllerV2@resendotp');
    Route::post('/sendmail','Api\v2\ApiControllerV2@sendmail');
    Route::post('/forgotpassword','Api\v2\ApiControllerV2@forgotpassword');
    Route::post('/addresschange','Api\v2\ApiControllerV2@addresschange');
    Route::post('/updatepassword','Api\v2\ApiControllerV2@updatepassword');
    Route::get('/matchcouponcode','Api\v2\ApiControllerV2@matchcouponcode');
    Route::post('/matchcouponcode','Api\v2\ApiControllerV2@matchcouponcode');

    Route::post('/ownerfetchdata','Api\v2\ApiControllerV2@ownerfetchdata');
    Route::get('/ownerfetchdata','Api\v2\ApiControllerV2@ownerfetchdata');
    Route::post('/ownerlogin','Api\v2\ApiControllerV2@ownerlogin');
    Route::get('/ownerlogin','Api\v2\ApiControllerV2@ownerlogin');
    Route::post('/ownerlogout','Api\v2\ApiControllerV2@ownerlogout');
    Route::get('/ownerlogout','Api\v2\ApiControllerV2@ownerlogout');
    Route::post('/nextstatus','Api\v2\ApiControllerV2@nextstatus');
    Route::get('/nextstatus','Api\v2\ApiControllerV2@nextstatus');
    Route::get('/autoorders','Api\v2\ApiControllerV2@autoorders');
    Route::post('/autoorders','Api\v2\ApiControllerV2@autoorders');
    Route::post('/firstorder','Api\v2\ApiControllerV2@firstorder');
    Route::get('/firstorder','Api\v2\ApiControllerV2@firstorder');
    Route::get('/termsandcondition','Api\v2\ApiControllerV2@termsandcondition');
    Route::post('/ownernotification','Api\v2\ApiControllerV2@ownernotification');
    Route::get('/ownernotification','Api\v2\ApiControllerV2@ownernotification');
    Route::post('/resetorderid','Api\v2\ApiControllerV2@resetorderid');
    Route::get('/resetorderid','Api\v2\ApiControllerV2@resetorderid');
    Route::post('/generatereport','Api\v2\ApiControllerV2@generatereport');
    Route::get('/generatereport','Api\v2\ApiControllerV2@generatereport');
    Route::post('/updatemenuitem','Api\v2\ApiControllerV2@updatemenuitem');
    Route::get('/updatemenuitem','Api\v2\ApiControllerV2@updatemenuitem');
    Route::get('/cancellationreason','Api\v2\ApiControllerV2@cancellationreason');
    Route::post('/cancellationreason','Api\v2\ApiControllerV2@cancellationreason');
    Route::get('/ordercancellation','Api\v2\ApiControllerV2@ordercancellation');
    Route::post('/ordercancellation','Api\v2\ApiControllerV2@ordercancellation');
    Route::post('/owneroutlet','Api\v2\ApiControllerV2@owneroutlet');
    Route::post('/printsummary','Api\v2\ApiControllerV2@printsummary');
    Route::post('/addrecipe','Api\v2\ApiControllerV2@addrecipes');
    Route::post('/getrecipe','Api\v2\ApiControllerV2@getrecipes');

    Route::post('/pastorders','Api\v2\ApiControllerV2@pastorders');
    Route::post('/syncorderadd','Api\v2\ApiControllerV2@syncorderadd');
    Route::post('/closeCounter','Api\v2\ApiControllerV2@closeCounter');
    Route::get('/expenseApp','ExpenseController@index');
});

#TODO: consumer API
Route::group(['prefix' => 'api/v3'], function () {

    Route::get('/restaurantmenu','Api\v3\ApiController@outletmenu');
    Route::post('/orderdetails','Api\v3\ApiController@orderdetails');
    #TODO: upate device id Api
    Route::post('/update-device-id','Api\v3\ApiController@updateDeviceId');
    Route::post('/login','Api\v3\ApiController@login');
    Route::post('/addcustomer','Api\v3\ApiController@addcustomer');
    Route::post('/verifyotp','Api\v3\ApiController@verifyotp');
    Route::post('/forgotpassword','Api\v3\ApiController@forgotpassword');
    Route::post('/updatepassword','Api\v3\ApiController@updatepassword');
    Route::get('/updatedetail','Api\v3\ApiController@updatedetail');
    Route::post('/updatedetail','Api\v3\ApiController@updatedetail');
    Route::get('/restaurantinformation','Api\v3\ApiController@outletinformation');
    Route::post('/firstorder','Api\v3\ApiController@firstorder');
    Route::get('/firstorder','Api\v3\ApiController@firstorder');
    Route::post('/sendmail','Api\v3\ApiController@sendmail');
    Route::get('/logincustomers',array('as' => 'logincustomers', 'uses' => 'Api\v3\ApiController@logincustomers'));
    Route::post('/logincustomers',array('as' => 'logincustomers', 'uses' => 'Api\v3\ApiController@logincustomers'));
    Route::get('/getallcities', array('as' => 'getallcities', 'uses' => 'Api\v3\ApiController@getallcities'));
    Route::get('/getlocalitybycity', array('as' => 'getlocalitybycity', 'uses' => 'Api\v3\ApiController@getlocalitybycity'));
    Route::post('/getlocalitybycity', array('as' => 'getlocalitybycity', 'uses' => 'Api\v3\ApiController@getlocalitybycity'));
    Route::post('/addaddress', array('as' => 'addaddress', 'uses' => 'Api\v3\ApiController@addaddress'));
    Route::get('/addaddress', array('as' => 'addaddress', 'uses' => 'Api\v3\ApiController@addaddress'));
    Route::get('/getlocality',array('as' => 'getlocality', 'uses' => 'Api\v3\ApiController@getlocality'));
    Route::post('/getlocality',array('as' => 'getlocality', 'uses' => 'Api\v3\ApiController@getlocality'));
    Route::get('/matchcouponcode','Api\v3\ApiController@matchcouponcode');
    Route::post('/matchcouponcode','Api\v3\ApiController@matchcouponcode');
    Route::get('/payusuccess', array('as' => 'payusuccess', 'uses' => 'Api\v3\ApiController@payusuccess'));
    Route::post('/payusuccess', array('as' => 'payusuccess', 'uses' => 'Api\v3\ApiController@payusuccess'));
    Route::get('/payufailure', array('as' => 'payufailure', 'uses' => 'Api\v3\ApiController@payufailure'));
    Route::post('/payufailure', array('as' => 'payufailure', 'uses' => 'Api\v3\ApiController@payufailure'));
    Route::get('/termsandcondition','Api\v3\ApiController@termsandcondition');
    Route::post('/getreviews', array('as' => 'getreviews', 'uses' => 'Api\v3\ApiController@getreviews'));
    Route::post('/addlike', array('as' => 'addlike', 'uses' => 'Api\v3\ApiController@addlike'));
    Route::post('/addreviews', array('as' => 'addreviews', 'uses' => 'Api\v3\ApiController@addreviews'));
    Route::post('/getrecipe','Api\v3\ApiController@getrecipes');
    Route::post('/savehotkeyconfig','Api\v3\ApiController@saveHotKeyConfig');
    #TODO: Table occupied
    Route::post('/table-availability','Api\v3\ApiController@tableAvailability');
    Route::resource('/restaurant', 'Api\v3\ApiController');
});


#TODO: login to partner application
Route::post('api/v3/owneroutlet','Api\v3\ApiController@owneroutlet');
#TODO: Pinger for check application status
Route::post('api/v3/pinger','Api\v3\ApiController@pinger');

#TODO: get all outlet list for push notification
Route::post('api/v3/send-log-notification','Api\v3\ApiController@getAllOutletPush');
Route::post('api/v3/upload-log','Api\v3\ApiController@uploadlog');

#TODO: Tables module
Route::get('api/v3/tables-list','Api\v3\TablesController@tablesList');
Route::get('api/v3/tables/create', 'Api\v3\TablesController@tableForm');
Route::post('api/v3/tables/create', 'Api\v3\TablesController@tableStore');
Route::post('api/v3/tables/update', 'Api\v3\TablesController@updateTable');
Route::get('api/v3/tables/{id}/edit', 'Api\v3\TablesController@editForm');
Route::get('api/v3/tables/{id}/destroy', 'Api\v3\TablesController@destroyTable');
Route::get('api/v3/tables/{id}/unoccupy', 'Api\v3\TablesController@unoccupyTable');

#TODO: Stock transfer API
Route::get('api/v3/stock-transfer','Api\v3\StocksController@StockTransfer');
Route::post('api/v3/stock-transfer','Api\v3\StocksController@StockTransfer');
Route::post('api/v3/stock-transfer-items','Api\v3\StocksController@StockTransferItems');

#TODO: Reports API
Route::post('api/v3/sales-consumption-report','Api\v3\StocksController@salesConsumptionReport');
Route::get('api/v3/stock-status-report','Api\v3\StocksController@stockStatusReport');
Route::post('api/v3/stock-status-report','Api\v3\StocksController@stockStatusReport');

Route::group(['prefix' => 'api/v3','middleware' => 'auth.api'], function () {

    Route::post('/payuview', array('as' => 'payuview', 'uses' => 'Api\v3\ApiController@payuview'));
    Route::get('/addstatusforallrestaurant', array('as' => 'addstatusforallrestaurant', 'uses' => 'Api\v3\ApiController@addstatusforallrestaurant'));

    Route::get('/payuview','Api\v3\ApiController@payuview');
    Route::post('/resendotp','Api\v3\ApiController@resendotp');
    Route::post('/addresschange','Api\v3\ApiController@addresschange');
    Route::get('/matchcouponpro','Api\v3\ApiController@matchcouponpro');
    Route::post('/matchcouponpro','Api\v3\ApiController@matchcouponpro');

    Route::post('/ownerfetchdata','Api\v3\ApiController@ownerfetchdata');
    Route::get('/ownerfetchdata','Api\v3\ApiController@ownerfetchdata');
    Route::post('/ownerlogin','Api\v3\ApiController@ownerlogin');
    Route::get('/ownerlogin','Api\v3\ApiController@ownerlogin');
    Route::post('/ownerlogout','Api\v3\ApiController@ownerlogout');
    Route::get('/ownerlogout','Api\v3\ApiController@ownerlogout');
    Route::post('/nextstatus','Api\v3\ApiController@nextstatus');
    Route::get('/nextstatus','Api\v3\ApiController@nextstatus');
    Route::get('/autoorders','Api\v3\ApiController@autoorders');
    Route::post('/autoorders','Api\v3\ApiController@autoorders');

    Route::post('/ownernotification','Api\v3\ApiController@ownernotification');
    Route::get('/ownernotification','Api\v3\ApiController@ownernotification');
    Route::post('/resetorderid','Api\v3\ApiController@resetorderid');
    Route::get('/resetorderid','Api\v3\ApiController@resetorderid');
    Route::post('/generatereport','Api\v3\ApiController@generatereport');
    Route::get('/generatereport','Api\v3\ApiController@generatereport');
    Route::post('/updatemenuitem','Api\v3\ApiController@updatemenuitem');
    Route::get('/updatemenuitem','Api\v3\ApiController@updatemenuitem');
    Route::get('/cancellationreason','Api\v3\ApiController@cancellationreason');
    Route::post('/cancellationreason','Api\v3\ApiController@cancellationreason');
    Route::get('/ordercancellation','Api\v3\ApiController@ordercancellation');
    Route::post('/ordercancellation','Api\v3\ApiController@ordercancellation');
    //Route::post('/owneroutlet','Api\v3\ApiController@owneroutlet');
    Route::post('/printsummary','Api\v3\ApiController@printsummary');
    Route::post('/addrecipe','Api\v3\ApiController@addrecipes');

    Route::post('/pastorders','Api\v3\ApiController@pastorders');
    Route::post('/syncorderadd','Api\v3\ApiController@syncorderadd');
    Route::post('/closeCounter','Api\v3\ApiController@closeCounter');
    Route::get('/expenseApp','ExpenseController@index');
    Route::post('/orderupdate','Api\v3\ApiController@orderUpdate');
    Route::post('/getlastinvoiceno','Api\v3\ApiController@getLastInvoiceNo');
    Route::get('/checkconnection','Api\v3\ApiController@checkConnection');
    Route::post('/syncprintersettings','Api\v3\ApiController@settingsAndPrinters');

    Route::post('/get-online-orders','Api\v3\ApiController@getOnlineOrders');
    Route::post('/attend-me-notification','Api\v3\ApiController@sendAttendMe');
    Route::post('/consumer-pay-bill-notification','Api\v3\ApiController@consumerPayBillNotification');

    #TODO: Kot sync
    Route::post('/sync-kot','Api\v3\ApiController@syncKot');

    Route::post('/vacant-table','Api\v3\ApiController@vacantTable');

    #TODO: Cancel Consumer Order
    Route::post('/cancel-order','Api\v3\ApiController@cancelAllOrder');

    #TODO Process Request
    Route::post('/store-request','Api\v3\ApiController@storeRequest');

    #TODO: Get Request by Location API
    Route::post('/get-request-by-location','Api\v3\StocksController@getRequestByLocation');
    Route::post('/process-request','Api\v3\ApiController@processRequest');

    #TODO: Expense Api
    //Route::post('/add-expense','Api\v3\ApiController@addExpense');
    Route::post('/sync-expense','Api\v3\ApiController@synExpense');

    #TODO: get pending request qty
    Route::post('/get-pending-item-qty','Api\v3\StocksController@getPendingItemQty');
    Route::post('/past-request','Api\v3\ApiController@pastRequest');

    #TODO: send otp for campaign
    Route::post('/send-campaign-otp','Api\v3\ApiController@sendCampaignOTP');

    #TODO: store campaign detail
    Route::post('/store-campaign-detail','Api\v3\ApiController@storeCampaignDetail');

    //CusionList
    Route::get('/getallcuision', array('as' => 'getallcuision', 'uses' => 'Api\v3\ApiController@getAllCuisionList'));

    #TODO: get Biller order
    Route::post('/get-biller-order','Api\v3\ApiController@getBillerOrder');

    #TODO: send waiting number sms to customer
    Route::post('/send-waitlist-sms','Api\v3\ApiController@sendWaitlistSms');

});

Route::resource('tables', 'TablesController');
Route::post('/tables/create', 'TablesController@store');
Route::post('/tables/{id}/update', 'TablesController@update');
Route::get('/tables/{id}/destroy', 'TablesController@destroy');
Route::get('/tables/{id}/unoccupy', 'TablesController@unoccupy');
Route::get('/table_index', 'TablesController@tableindex');

//send pushnotification
Route::get('/pushlog','OutletController@pushLog');
Route::get('/loglist','OutletController@loglist');
Route::post('/ajax/getDevices','OutletController@pushLog');

Route::get('/paytmsuccess', array('as' => 'paytmsuccess', 'uses' => 'Api\v3\ApiController@paytmsuccess'));
Route::post('/paytmsuccess', array('as' => 'paytmsuccess', 'uses' => 'Api\v3\ApiController@paytmsuccess'));
Route::get('/paytmfailure', array('as' => 'paytmfailure', 'uses' => 'Api\v3\ApiController@paytmfailure'));
Route::post('/paytmfailure', array('as' => 'paytmfailure', 'uses' => 'Api\v3\ApiController@paytmfailure'));
// End //

Route::post('apply/multiple_upload', 'ApplyController@multiple_upload');
Route::post('apply/upload', 'ApplyController@upload');
Route::post('apply/destroy', 'ApplyController@destroy');

Route::get('/outlet/{id}/destroy', 'OutletController@destroy');
Route::get('/status/{id}/destroy', 'statuscontroller@destroy');
Route::get('coupongenerator/{coupongenerator}/destroy', 'CoupongeneratorController@destroy');
Route::get('/owner-app-version', 'OutletController@ownerAppVersion');


Route::get('/ajax/currentorderdetails','OrderdetailsController@currentorderdetails');

Route::post('/ajax/dailyreport','OutletController@dailyreport');
Route::get('/allorders','OrderdetailsController@allorders');
Route::post('/update-printers','OutletController@updatePrinters');

Route::get('/addCustomField', 'OutletController@addCustomField');
Route::post('/addCustomField', 'OutletController@addCustomField');

Route::resource('outlet', 'OutletController');
Route::get('/admin-outlet', 'OutletController@adminOutlet');
Route::post('/admin-outlet', 'OutletController@adminOutlet');
Route::get('/admin-outlet/outlet/create', 'OutletController@create');

//Users
Route::resource('users', 'UserController');

//vendors
Route::resource('vendor', 'VendorsController');
Route::get('/vendor/{id}/destroy', 'VendorsController@destroy');

//purchases
Route::resource('purchase', 'PurchasesController');
Route::get('/purchase/{id}/destroy', 'PurchasesController@destroy');
Route::post('/processinvoice', 'PurchasesController@processinvoice');
Route::get('/processinvoice', 'PurchasesController@processinvoice');
Route::post('/invoice-update', 'PurchasesController@invoiceupdate');
Route::post('/remove-purchase-item', 'PurchasesController@removePurchaseItem');
Route::post('/invoice-bill-detail', 'PurchasesController@show');
Route::post('/get-purchase-stock-detail', 'PurchasesController@getPurchaseStockDetail');
Route::post('/import-purchase', 'PurchasesController@import');
Route::get('/invalid-import-items', 'PurchasesController@invalidImportItems');
Route::get('/download_sample_purchase', 'PurchasesController@samplePurchase');
Route::get('/invalidItem/{id}/edit', 'PurchasesController@invalidItemImportEdit');
Route::patch('/invalidItem/{id}/edit',array('as' => 'purchase.updateInvalid', 'uses' => 'PurchasesController@invalidImportSubmit'));
Route::get('/invalid-purchase/{id}/destroy',array('as' => 'purchase.destroyInvalid', 'uses' => 'PurchasesController@destroyInvalid'));


//Locations
Route::post('/location/store-stock-level', 'LocationsController@storeStockLevel');
Route::get('/location/stock-level', 'LocationsController@setStockLevel');
Route::post('/location/stock-level', 'LocationsController@setStockLevel');
Route::resource('location', 'LocationsController');
Route::get('/location/{id}/destroy', 'LocationsController@destroy');

//Item Attributes
Route::resource('item-attributes', 'ItemAttributesController');
Route::post('/item-attributes/destroy', 'ItemAttributesController@destroy');

//stocks
Route::get('/stocks', 'StocksController@index');
Route::get('/remove-stock', 'StocksController@removeStock');
Route::get('/add-stock', 'StocksController@addStock');
Route::get('/manually-stock-decrement', 'StocksController@ManuallyStockDecrement');
Route::post('/manually-stock-decrement', 'StocksController@ManuallyStockDecrement');
Route::get('/on-sell-decrease-stock','StocksController@onSellDecreaseStock');
Route::post('/stock-detail', 'StocksController@StockDetails');
Route::post('/reserve-stock', 'StocksController@ReserveStock');
Route::get('/stock-transfer', 'StocksController@StockTransfer');
Route::get('/get-transfer-items', 'StocksController@getTransferItems');
Route::post('/stock-transfer', 'StocksController@StockTransfer');
Route::get('/stocks-invert-reverse','StocksController@stockInvertReverse');
Route::post('/request_barcode','StocksController@requestBarcode');
Route::post('/response_barcode','StocksController@responseBarcode');


// Expense

Route::resource('expenseApp', 'ExpenseController');
Route::post('/ajax/outletby','ExpenseController@getUsersByOutlet');
Route::post('/ajax/expenseTo','ExpenseController@getAuthorisedUsersByOutlet');
Route::post('/expense','ExpenseController@store');
Route::get('/expense/pending','ExpenseController@show');
Route::post('/expense/status/{id}/','ExpenseController@changeStatus');
Route::post('/expense/note/{id}/','ExpenseController@updateNote');
Route::get('/expense/{id}/edit', 'ExpenseController@edit');
Route::get('/expense/{id}/destroy', 'ExpenseController@destroy');
Route::get('cash/add', array('as' => 'expense.addcash', 'uses' => 'ExpenseController@addCash'));
Route::get('cash/{id}/edit', array('as' => 'expense.editcash', 'uses' => 'ExpenseController@editCash'));

//expense category
Route::get('/expense-category-index','ExpenseController@categoryIndex');
Route::get('/expense-category/{id}/destroy', 'ExpenseController@destroyExpenseCategory');
Route::get('/expense-category/{id}/edit', 'ExpenseController@editExpenseCategory');
Route::get('/add-expense-category','ExpenseController@categoryForm');
Route::post('expensecategory/store','ExpenseController@expenseCategoryStore');
Route::post('expensecategory/update','ExpenseController@expenseCategoryUpdate');


Route::resource('menu', 'MenuController');
Route::resource('coupongenerator', 'CoupongeneratorController');

Route::resource('orderdetails', 'OrderdetailsController');
Route::resource('status','statuscontroller');
Route::resource('status','statuscontroller');
Route::resource('neworder','newordercontroller');
Route::post('/neworder/refreshorder','newordercontroller@getorders');

Route::resource('cancellationreason','CancellationReasonController');
Route::get('cancellationreason/{cancellationreason}/destroy', 'CancellationReasonController@destroy');

//Design FeedBack
Route::resource('designfeedback','DesignFeedBackController');
Route::get('/designFeedBack', 'DesignFeedBackController@create');
Route::post('/createform', 'DesignFeedBackController@store');
Route::post('/getFeedback', 'DesignFeedBackController@getFeedback');

#TODO: FeedBack question
Route::resource('feedback-question','FeedbackQuestionController');
Route::get('/feedback-question/{id}/destroy', 'FeedbackQuestionController@destroy');

Route::post('/ajax/orderdetails/changestatus','OrderdetailsController@changestatus');
Route::get('/ajax/orderdetails','OrderdetailsController@index');
Route::get('/ajax/getstatus','OrderdetailsController@getstatus');
Route::get('/ajax/getallorderdetails','OrderdetailsController@getallorderdetails');
Route::get('/ajax/status','OrderdetailsController@status');
Route::get('/ajax/nextstatus','OrderdetailsController@nextstatus');

Route::get('/ajax/searchorder','OrderdetailsController@searchorder');
Route::get('/ajax/dashboardreport','HomeController@dashboardreport');
Route::get('/ajax/getordernotification','OrderdetailsController@getordernotification');
Route::get('/ajax/updateorderdet','OrderdetailsController@updateorderdetailstable');
Route::get('/ajax/getselectedpermissions','PermissionController@getselectedpermissions');
Route::get('ajax/resetorderid','OrderdetailsController@resetorderid');

// Item Menu
Route::POST('/menu/importmenuexcel','MenuController@importmenuexcel');
Route::POST('/outlet/importdetailsexcel','OutletController@importOutletexcel');
Route::get('/check_menu_title','MenuController@check_menu_title');
Route::get('/uploadItemMaster','MenuController@index');
Route::get('/menutitle',array('as' => 'menu.indextitle', 'uses' => 'MenuController@indexTitle'));
Route::get('/ajax/title_change','MenuController@title_change');
Route::post('/get-item-by-category','MenuController@getItemsByCategoryId');
Route::get('/menu/{id}/show', 'MenuController@show');
Route::get('/menu/{id}/imageDestroy', 'MenuController@imageDestroy');
Route::get('/title/{id}/edit', 'MenuController@titleEdit');
Route::get('/title', 'MenuController@titleForm');
Route::post('/menu/destroy', 'MenuController@destroy');
Route::post('/get-item-other-units', 'MenuController@getItemOtherUnits');
Route::post('/update-menu-sequence', 'MenuController@updateMenuSequence');
Route::post('/change-item-settings', 'MenuController@changeItemSettings');
Route::post('/remove-item-option', 'MenuController@removeItemOption');

#TODO: Item Option group
Route::get('/item-option-groups',array('as' => 'itemoptiongroups.index', 'uses' => 'ItemOptionGroupController@index'));
Route::get('/item-option-group/create',array('as' => 'itemoptiongroups.create', 'uses' => 'ItemOptionGroupController@create'));
Route::post('/item-option-group/store',array('as' => 'itemoptiongroups.store', 'uses' => 'ItemOptionGroupController@store'));
Route::get('/item-option-group/{id}/edit',array('as' => 'itemoptiongroups.edit', 'uses' => 'ItemOptionGroupController@edit'));
Route::patch('/item-option-group/update/{id}',array('as' => 'itemoptiongroups.update', 'uses' => 'ItemOptionGroupController@update'));
Route::get('/item-option-group/{id}/destroy', 'ItemOptionGroupController@destroy');
Route::post('/remove-item-group-option', 'ItemOptionGroupController@removeItemGroupOption');

Route::resource('role','RoleController');
Route::resource('permission','PermissionController');
Route::resource('tax','TaxController');
Route::resource('recipe','RecipeController');
Route::get('recipe/{id}/destroy', 'RecipeController@destroy');
Route::get('tax/{tax}/destroy', 'TaxController@destroy');

// Tablet API

Route::POST('/outlet/importoutletotherdetails','OutletController@importoutletotherdetails');

Route::any('/ajax/getsummaryreport','ReportController@getsummaryreport');
Route::post('/ajax/getdetailedreport','ReportController@getdetailedreport');
Route::post('/ajax/getlinechartdata','ReportController@getLineChartReportData');
Route::post('/getPieChartData','ReportController@getPieChartData');
Route::post('/getPieChartDateData','ReportController@getPieChartDateData');
Route::post('/getBarChartDateData','ReportController@getBarChartDateData');//use for top10 item list date wise
Route::post('/getBarChartMonthData','ReportController@getBarChartMonthData');//use for top10 item list month wise
Route::post('/dashboardOrderData','HomeController@dashboardOrderData');
Route::post('/ajax/selectslots','ReportController@selectslots');


// Recipe
Route::resource('recipeDetails','RecipeDetailsController');
Route::get('/ajax/ajaxMenuList','MenuController@ajaxMenuItemsList');
Route::get('/ajax/ajaxItemTitleList','MenuController@ajaxItemTitleList');
Route::get('/ajax/ajaxOutletItems','MenuController@ajaxOutletItemsList');
Route::get('/ajax/recipeList','RecipeDetailsController@ajaxRecipeList');
Route::get('/ajax/ajaxQtyUnit','RecipeDetailsController@ajaxQtyUnit');
Route::get('/ajax/checkRecipe','RecipeDetailsController@checkRecipe');
Route::post('/recipeDetails/find','RecipeDetailsController@findRecipe');
Route::get('/recipeDetails/{id}/destroyRecipe', 'RecipeDetailsController@destroyRecipe');
Route::get('/recipeDetails/{id}/destroyIngredients', 'RecipeDetailsController@destroyIngredients');
Route::get('/recipeDetails/{id}/destroy', 'RecipeDetailsController@destroy');
Route::get('/recipeDetails/{id}/show', 'RecipeDetailsController@show');
Route::get('/ajax/ajaxGetItemUnit','RecipeDetailsController@ajaxGetItemUnit');
Route::get('/prepareRecipe','RecipeDetailsController@PrepareRecipe');
Route::get('/getRecipe','RecipeDetailsController@getRecipe');
Route::post('/processPrepareItem','RecipeDetailsController@processPrepareItem');


// Outlet Bind
Route::get('outletBind/{id}','OutletController@bindOutlet');
Route::get('outletBind','OutletController@bindOutlet');
Route::post('outletBind','OutletController@storeBindOutlet');
Route::get('/outletBind/{id}/destroy', 'OutletController@destroyOutletBind');
Route::post('/store-outlet-status', 'OutletController@storeOutletStatus');

// Menu Bind

Route::get('menuBind','MenuBindController@index');
Route::post('menuBind','MenuBindController@store');
Route::get('/ajax/MenuItemList','MenuBindController@ajaxMenuItemsList');

// Request Item

Route::resource('requestItem','RequestItemController');
Route::get('/ajax/ajaxOwnerRequest','RequestItemController@ajaxOwnerList');
Route::get('/ajax/ajaxItems','RequestItemController@ajaxItems');
Route::get('/ajax/getItems','RequestItemController@getItem');
Route::get('/requestItem/items','RequestItemController@getItem');
Route::post('/category/add','RequestItemController@addCategory');
Route::get('/delete_cat/{id}/','RequestItemController@deleteCategory');
Route::get('/requestItem/{id}/destroy', 'RequestItemController@destroy');
Route::post('/deleteAllRequest', 'RequestItemController@destroyAll');

// Request Process Item
Route::resource('requestItemProcess','RequestItemProcessController');
Route::get('/ajax/getRequestedItems','RequestItemProcessController@getRequestedItems');
Route::get('/requestItemProcess/{id}/getRequestedItem','RequestItemProcessController@getRequestedItem');
Route::get('/responseItem/{id}/edit','RequestItemProcessController@getResponseItemEdit');
Route::post('/responseItem/delete','RequestItemProcessController@getResponseItemDelete');
Route::post('/responseUpdate','RequestItemProcessController@responseUpdate');
Route::get('/responseItems/getSatisfiedItemTime','RequestItemProcessController@getSatisfiedItemTime');
Route::get('/responseItems/{selected_time}/getSatisfiedUser','RequestItemProcessController@getSatisfiedUser');
Route::get('/responseItems/{id}/{time}/getSatisfiedCategory','RequestItemProcessController@getSatisfiedCategory');
Route::get('/responseItems/{id}/{time}/{cate_id}/getSatisfiedItem','RequestItemProcessController@getSatisfiedItem');
Route::post('/requestprocessitemstockdetail','RequestItemProcessController@requestProcessItemStockDetail');
Route::post('/get-process-stock-detail','RequestItemProcessController@getProcessStockDetail');
Route::get('/responseItems/setisfiedResponse', array('as' => 'requestItemProcess.setisfiedResponse', 'uses' => 'RequestItemProcessController@setisfiedResponse'));
Route::post('/responseItems/setisfiedResponse','RequestItemProcessController@setisfiedResponse');
Route::post('/satisfied-response-list','RequestItemProcessController@satisfiedResponseList');
Route::get('/satisfiedRequestExport/{id}','RequestItemProcessController@satisfiedRequestExport');



Route::get('/ajax/menuitemslist', 'MenuController@ajaxMenuItemsList');
Route::resource('createorder','AddOrderController');
Route::get('/ajax/getprice','AddOrderController@get_price');
Route::get('/ajax/getServiceTypeOutletList','AddOrderController@getServiceTypeOutletList');

// print order
Route::get('/printorder','newordercontroller@printOrder');
Route::get('/printkot','newordercontroller@printKot');
Route::get('/populate_invoice_table','HomeController@populateInvoiceTable');
Route::post('/cancelorder','newordercontroller@cancelOrder');
Route::post('/deleteorder','newordercontroller@deleteOrder');
Route::get('/processbill','newordercontroller@processBill');
Route::post('/processbillfinal','newordercontroller@processBillFinal');
Route::get('/getinvoiceno','newordercontroller@getInvoiceNo');
Route::get('/editbill','newordercontroller@editBill');
Route::get('/editorders/{orderid}','newordercontroller@editorders');
Route::post('/saveOrder','newordercontroller@saveOrder');
Route::post('/closeTable','newordercontroller@closetable');
Route::post('/updateinvoice','newordercontroller@updateInvoice');
Route::get('/orderslist','newordercontroller@orderList');
Route::post('/orderslist','newordercontroller@orderList');
Route::get('/add-order','newordercontroller@addOrder');
Route::get('/addorder/{tab}/{no_of_person}','newordercontroller@addudpOrder');
Route::get('/get-request','newordercontroller@getrequest');
Route::post('/place-order','newordercontroller@placeOrder');
Route::get('/ongoing-orders','newordercontroller@ongoingOrders');
Route::post('/ongoing-orders','newordercontroller@ongoingOrders');
Route::get('/ongoing-tables','newordercontroller@ongoingTables');
Route::post('/ongoing-tables','newordercontroller@ongoingTables');
Route::post('/pay-with-upi','newordercontroller@payWithUpi');
Route::post('/upi-payment-status','newordercontroller@upiPaymentStatus');
Route::post('/paid-order','newordercontroller@paidOrder');
Route::get('/bill-template','OutletController@billTemplate');
Route::get('/update-bill-template','OutletController@editBillTemplate');
Route::post('/update-bill-template','OutletController@updateBillTemplate');
Route::post('/order-history','newordercontroller@orderHistory');
Route::post('/get-order-payment-modes','newordercontroller@getOrderPaymentModes');
Route::post('/calculate-order-discount','newordercontroller@calculateOrderDiscount');
//Tax calculation script
Route::get('/order-tax-calculation/','newordercontroller@taxCalculation');

//order place types
Route::resource('order-place-types', 'OrderPlaceTypeController');
Route::get('/order-place-types/{id}/destroy', 'OrderPlaceTypeController@destroy');
Route::post('/removeOrderItem','newordercontroller@removeOrderItem');

//Route::get('/order-place-types','OrderPlaceTypeController@index');

//printers
Route::resource('printers','PrinterController');
Route::get('/printers/{id}/destroy', 'PrinterController@destroy');
Route::get('/printer-bind','PrinterController@printerBind');
Route::post('/printer-bind','PrinterController@printerBind');
Route::post('/store-printer-bind','PrinterController@storePrinterBind');

//temp
Route::get('/updatecategory','MenuController@updateItemCategory');
Route::get('/changetaxformat','HomeController@changeTaxFormat');
Route::get('/updatemapper','HomeController@updateMapper');
Route::get('/populateinvoice','HomeController@populateInvoiceField');
Route::get('/populatesubtotal','HomeController@populateSubTotalField');
Route::get('/populateitemtotal','HomeController@populateItemTotal');
Route::get('/updateinvoicenumber','HomeController@updateInvoiceNumber');
Route::get('/populate-stock-age-table','HomeController@populateStockAgeTable');
Route::get('/populate-tax-gs','HomeController@populateTaxesGS');
Route::get('/populate-item-settings','HomeController@populateItemSettings');
Route::get('/make-item-issale','HomeController@makeItemisSale');
Route::get('/make-itme-entry-in-setting-table','HomeController@makeItementryInSettingTable');
Route::get('/populate-respose-deviation','HomeController@populateResposeDeviation');
Route::get('/populate-account-id-owner-table','HomeController@populateAccountIdOwnerTable');
Route::get('/change-payment-mode-id','HomeController@changePaymentModeId');
Route::get('/populate-total-bifurcation','HomeController@populateTotalBifurcation');
Route::get('/set-new-taxes','HomeController@setNewTax');
Route::post('/set-outlet-session','HomeController@setOutletSession');
Route::get('/generate-summary-report','HomeController@generateSummaryReport');
Route::post('/generate-summary-report','HomeController@generateSummaryReport');
Route::get('/set-menu-alias/{id}/','HomeController@setItemAlias');
Route::get('/update-page-data/{flag}/','HomeController@updatePageData');
Route::get('/update-payment_mode/','HomeController@add_order_payment_mode');
Route::get('/add-menu-options-to-option-groups/','HomeController@add_menu_item_option_to_option_groups');
Route::post('/getcnprice/','CreditNoteController@getCNPrice');

Route::get('/duplicate-invoiceno-report','ReportController@duplicateInvoicenoReport');
Route::post('/duplicate-invoiceno-report','ReportController@duplicateInvoicenoReport');
Route::post('/reset_invoice_no','newordercontroller@resetInvoiceNo');


//report
Route::get('/reports','ReportController@index');
Route::get('/sales-report','ReportController@saleReport');
Route::post('/sales-report','ReportController@saleReport');
Route::post('/itemreport','ReportController@show');
Route::post('/export_item_excel','ReportController@export_item_excel');
Route::post('/ajax/get_detail_report_pdf','ReportController@get_detail_report_pdf');
Route::get('/daily_report_pdf','ReportController@daily_report_pdf');
//Route::get('B/{param}/C/{param1?}', 'YourController@index');
Route::get('/download_detail_report/{param}/{param1?}', 'ReportController@download_daily_report');
Route::get('/detail_discount_report', 'ReportController@detail_discount_report');
Route::post('/summary_report', 'ReportController@summary_report');
Route::get('/summary_report', 'ReportController@summary_report');
Route::post('/ajax/detail_discount_report','ReportController@ajax_detail_discount_report');
Route::post('/ajax/summary_report','ReportController@ajax_summary_report');
Route::get('/snapshot','ReportController@snapshot');
Route::post('/snapshot','ReportController@snapshot');
Route::get('/revenue-report','ReportController@revenueReport');
Route::post('/revenue-report','ReportController@revenueReport');
Route::get('/expense-report','ReportController@expenseReport');
Route::post('/expense-report','ReportController@expenseReport');
Route::get('/purchase-rate-report','ReportController@purchaseRateReport');
Route::post('/purchase-rate-report','ReportController@purchaseRateReport');
Route::get('/stock-request-report','ReportController@stockRequestReport');
Route::post('/stock-request-report','ReportController@stockRequestReport');
Route::get('/stock-response-report','ReportController@stockResponseReport');
Route::post('/stock-response-report','ReportController@stockResponseReport');
Route::get('/stock-ageing-report','ReportController@stockAgeingReport');
Route::post('/stock-ageing-report','ReportController@stockAgeingReport');
Route::post('/getAvailableStock','StocksController@getAvailableStock');
Route::get('/cancellationreport','ReportController@cancellationReport');
Route::post('/cancellationreport','ReportController@cancellationReport');
Route::get('/sales-consumption-report','ReportController@salesConsumptionReport');
Route::post('/sales-consumption-report','ReportController@salesConsumptionReport');
Route::get('/stock-status-report','ReportController@stockStatusReport');
Route::post('/stock-status-report','ReportController@stockStatusReport');
Route::get('/payment-report','ReportController@paymentReport');
Route::post('/payment-report','ReportController@paymentReport');
Route::get('/closing-stock-report','ReportController@closingStockReport');
Route::post('/closing-stock-report','ReportController@closingStockReport');
Route::get('/campaign-report','ReportController@campaignReport');
Route::post('/campaign-report','ReportController@campaignReport');
Route::post('/get-camp-image','ReportController@getImage');
Route::get('/unpaid-orders','ReportController@unpaidOrdersReport');
Route::post('/unpaid-orders','ReportController@unpaidOrdersReport');
Route::get('/zoho-unsync-orders','ReportController@zohoUnsyncOrdersReport');
Route::post('/zoho-unsync-orders','ReportController@zohoUnsyncOrdersReport');


Route::get('/MenuItemList','MenuController@getMenuItem');
Route::get('/cash-sales','ReportController@cashSales');
Route::post('/cash-sales','ReportController@cashSales');

Route::get('/kot-vs-orders-diff','ReportController@kotvsordersdiff');
Route::post('/kot-vs-orders-diff','ReportController@kotvsordersdiff');
//response deviation
Route::get('/response-deviation','ReportController@responseDeviation');
Route::post('/response-deviation','ReportController@responseDeviation');


//Sources
Route::get('/source','SourceController@index');
Route::get('/add-source','SourceController@store');
Route::get('/source/{id}/destroy', 'SourceController@destroy');

//Enable inventory for account
Route::get('/account-setting','HomeController@accountSettings');
Route::post('/show-account-setting','HomeController@accountSettings');
Route::post('/store-account-setting','HomeController@storeAccountSettings');
Route::post('/show-outlet-setting','HomeController@getOutletSettings');
Route::get('/auto-process-outlet','HomeController@autoProcessIndex');
Route::post('/store-outlet-setting','HomeController@storeOutletSettings');
Route::get('/auto-process-orders','HomeController@autoProcessOrders');


//inventory item
Route::resource('inventoryitems','InventoryItemsController');
Route::get('/inventoryitems/{id}/destroy', 'InventoryItemsController@destroy');
/*Route::get('/inventory/items/create','InventoryItemsController@create');
Route::get('/inventory/items/{id}/edit','InventoryItemsController@edit');
Route::post('/inventory/items',array('as' => 'inventoryitems.store', 'uses' => 'InventoryItemsController@store'));
Route::post('/inventory/items/',array('as' => 'inventoryitems.update', 'uses' => 'InventoryItemsController@update'));*/

//TimeSlots
Route::resource('timeslots','TimeSlotController');
Route::get('/checktimeslots','TimeSlotController@gettimeslotbyoutletid');
Route::post('/storetimeslot','TimeSlotController@update');
Route::get('/timeslots/{id}/destroy', 'TimeSlotController@destroy');

//Settings
Route::get('/settings/add','SettingsController@create');
Route::post('/settings/add','SettingsController@create');
Route::get('/getSettings','SettingsController@getSettings');
Route::get('/update-settings','SettingsController@update');
Route::get('/settings/{id}/destroy', 'SettingsController@destroy');

//payment option
Route::get('/payment-options',array('as' => 'paymentoptions.index', 'uses' => 'SettingsController@paymentOptionsIndex'));
Route::get('/payment-option/create',array('as' => 'paymentoptions.create', 'uses' => 'SettingsController@paymentOptionsCreate'));
Route::post('/payment-option/store',array('as' => 'paymentoptions.store', 'uses' => 'SettingsController@paymentOptionsStore'));
Route::get('/payment-option/{id}/edit',array('as' => 'paymentoptions.edit', 'uses' => 'SettingsController@paymentOptionsEdit'));
Route::patch('/payment-option/update/{id}',array('as' => 'paymentoptions.update', 'uses' => 'SettingsController@paymentOptionsUpdate'));
Route::resource('settings','SettingsController');
//Route::post('/settings/add','SettingsController@create');

//Banks
Route::get('/banks/{id}/destroy', 'BankController@destroy');
Route::resource('banks','BankController');

//Uits
Route::get('/unit/{id}/destroy', 'UnitController@destroy');
Route::get('/unit/{id}/edit', 'UnitController@edit');
Route::resource('unit', 'UnitController');

//Attandance
Route::get('/attendance',array('as' => 'attendance', 'uses' => 'AttendanceController@index'));
Route::post('/fill-attendance',array('as' => 'attendance.store', 'uses' => 'AttendanceController@store'));
Route::get('/attendance-sheet',array('as' => 'attendance.sheet', 'uses' => 'AttendanceController@show'));
Route::post('/attendance-list',array('as' => 'attendance.attendancelist', 'uses' => 'AttendanceController@show'));
Route::post('/attendance-detail',array('as' => 'attendance.attendancedetail', 'uses' => 'AttendanceController@attendanceDetail'));



//staff role
Route::get('/staff-roles',array('as' => 'attendance.staffrole', 'uses' => 'AttendanceController@staffRoles'));
Route::get('/staff-roles/create',array('as' => 'attendance.createstaffrole', 'uses' => 'AttendanceController@createStaffRole'));
Route::post('/staff-roles/store',array('as' => 'attendance.storestaffrole', 'uses' => 'AttendanceController@storeStaffRole'));
Route::get('/staff-roles/{id}/edit',array('as' => 'attendance.editstaffrole', 'uses' => 'AttendanceController@editStaffRole'));
Route::patch('/staff-roles/update/{id}',array('as' => 'attendance.updatestaffrole', 'uses' => 'AttendanceController@updateStaffRole'));

//shift
Route::get('/staff-shifts',array('as' => 'attendance.staffshift', 'uses' => 'AttendanceController@staffShifts'));
Route::get('/staff-shifts/create',array('as' => 'attendance.createstaffshift', 'uses' => 'AttendanceController@createStaffShift'));
Route::post('/staff-shifts/store',array('as' => 'attendance.storestaffshift', 'uses' => 'AttendanceController@storeStaffShift'));
Route::get('/staff-shifts/{id}/edit',array('as' => 'attendance.editstaffshift', 'uses' => 'AttendanceController@editStaffShift'));
Route::patch('/staff-shifts/update/{id}',array('as' => 'attendance.updatestaffshift', 'uses' => 'AttendanceController@updateStaffShift'));

//staff
Route::get('/staffs',array('as' => 'attendance.staff', 'uses' => 'AttendanceController@staffs'));
Route::get('/staffs/create',array('as' => 'attendance.createstaff', 'uses' => 'AttendanceController@createStaff'));
Route::post('/staffs/store',array('as' => 'attendance.storestaff', 'uses' => 'AttendanceController@storeStaff'));
Route::get('/staffs/{id}/edit',array('as' => 'attendance.editstaff', 'uses' => 'AttendanceController@editStaff'));
Route::patch('/staffs/update/{id}',array('as' => 'attendance.updatestaff', 'uses' => 'AttendanceController@updateStaff'));

//staffing
Route::get('/staffing',array('as' => 'attendance.staffing', 'uses' => 'AttendanceController@staffing'));
Route::get('/staffing/create',array('as' => 'attendance.createstaffing', 'uses' => 'AttendanceController@createStaffing'));
Route::post('/staffing/store',array('as' => 'attendance.storestaffing', 'uses' => 'AttendanceController@storeStaffing'));
Route::get('/staffing/{id}/edit',array('as' => 'attendance.editstaffing', 'uses' => 'AttendanceController@editStaffing'));
Route::patch('/staffing/update/{id}',array('as' => 'attendance.updatestaffing', 'uses' => 'AttendanceController@updateStaffing'));

//Customers
Route::get('/customers',array('as' => 'customers.index', 'uses' => 'CustomersController@index'));
Route::post('/customers',array('as' => 'customers.index', 'uses' => 'CustomersController@index'));
Route::get('/customer/{id}/show',array('as' => 'customers.show', 'uses' => 'CustomersController@show'));
Route::post('/customer-itemwise-sale','CustomersController@customerItemWiseSale');

//Bookings
Route::get('/booking/list', array('as' => 'booking.list', 'uses' => 'BookingController@bookingList'));
Route::resource('booking', 'BookingController');
Route::post('/booking-room', 'BookingController@bookingRoom');
Route::post('/generate-date', 'BookingController@loadCalender');
Route::post('/check-room-details', 'BookingController@checkRoomDetails');
Route::post('/cancelBooking', 'BookingController@cancelBooking');
Route::post('/calc-room-tax', 'BookingController@calcRoomTaxTotal');
Route::get('/hotel-check-in', 'BookingController@checkInReport');
Route::post('/hotel-check-in', 'BookingController@checkInReport');
Route::get('/hotel-check-out', 'BookingController@checkOutReport');
Route::post('/hotel-check-out', 'BookingController@checkOutReport');
Route::get('/hotel-reservation', 'BookingController@reservationReport');
Route::post('/hotel-reservation', 'BookingController@reservationReport');
Route::get('/deposit-report', 'BookingController@depositReport');
Route::post('/deposit-report', 'BookingController@depositReport');
Route::get('/police-report', 'BookingController@policeReport');
Route::post('/police-report', 'BookingController@policeReport');
Route::get('/no-show', 'BookingController@noShow');
Route::post('/no-show', 'BookingController@noShow');
Route::get('/occupancy-analysis', 'BookingController@occupancyAnalysis');
Route::post('/occupancy-analysis', 'BookingController@occupancyAnalysis');

//Table Levels
Route::resource('table-levels','TableLevelController');
Route::get('/table-levels/{id}/destroy', 'TableLevelController@destroy');

//Email Template
//Route::resource('emailtemplates', 'EmailTemplateController');
//Route::post('emailtemplates/addimages', array('as'=>'emailtemplates.addimages','uses'=>'EmailTemplateController@addimages'));
//Route::post('emailtemplates/{id}', array('as'=>'emailtemplates.show','uses'=>'EmailTemplateController@show'));
//Route::get('/emailtemplates/{id}/imageDestroy', 'EmailTemplateController@imageDestroy');
//Route::get('emailconfig/prefrencecreate', array('before' => 'auth','as'=>'prefrences.create','uses'=>'EmailTemplateController@prefrencecreate'));
//Route::post('emailconfig/prefrencestore', array('before' => 'auth','as'=>'emailtemplates.prefrencestore','uses'=>'EmailTemplateController@prefrencestore'));
//Route::patch('emailconfig/prefrencesupdate', array('before' => 'auth','as'=>'emailtemplates.prefrencesupdate','uses'=>'EmailTemplateController@prefrencesupdate'));
//Route::patch('emailconfig/prefrencestore/{id}', array('as'=>'emailtemplates.prefrencesupdate','uses'=>'EmailTemplateController@prefrencestore'));
//Route::post('/getCustomerEmail', array('as'=>'customers.getCustomerEmail','uses'=>'CustomersController@getCustomerEmail'));
//Route::post('/sendCustomerMail', array('as'=>'customers.sendCustomerMail','uses'=>'CustomersController@sendCustomerMail'));

//Scripts
Route::get('/update-item-code/{id}','MenuController@updateItemCode');
Route::get('/delete-orders-before-date/{date}','HomeController@deleteOrdersBeforeDate'); //Date will be YYYY-MM-DD

//Room Status
Route::resource('room-status', 'RoomStatusController');

//Booking Status
Route::resource('booking-status', 'BookingStatusController');

//Salutation Status
Route::resource('salutation', 'SalutationController');

//Room Amenity Status
Route::resource('room-amenity', 'RoomAmenityController');

//Room Type Status
Route::resource('room-type', 'RoomTypeController');

//Room Status
Route::resource('rooms', 'RoomController');

//Arrival Departure Mode
Route::resource('arrival-departure-mode', 'ArrivalDepartureModeController');

//Arrival Departure Mode
Route::resource('guest-source', 'GuestSourceController');

//Guest
Route::resource('guests', 'GuestController');

//RewardPoints
Route::get('/reward-points/viewRewardPoints', 'RewardPointController@viewRewardPoints');
Route::resource('reward-points', 'RewardPointController');
Route::post('getRewardPoints','RewardPointController@getRewardPoints');
Route::get('reward-points/show', array('as' => 'reward-points.show', 'uses' => 'RewardPointController@show'));
Route::post('getRewardPointsTransaction', 'RewardPointController@getTransaction');
//reward point data table
Route::get('getRewardPointsHistory', 'RewardPointController@getRewardPointsHistory');

//Credit Notes
Route::resource('credit-note', 'CreditNoteController');
Route::get('/credit-note/create/{id}', array('uses'=>'CreditNoteController@create'));
Route::post('/getCraditNote', 'CreditNoteController@getCraditNote');

Route::get('/docsign', 'LocationsController@docSign');
Route::post('/docsign/store', array('as' => 'docusign.generate', 'uses' => 'LocationsController@docSignPdf'));
