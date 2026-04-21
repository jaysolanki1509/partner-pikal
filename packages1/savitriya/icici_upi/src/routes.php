<?php
Route::group (['prefix' => 'pgi/icici-upi'], function () {
    Route::post ('collect-pay', 'Savitriya\Icici_upi\IciciUpiController@collectPay');
    //Route::get ('collect-pay', 'Savitriya\Icici_upi\IciciUpiController@collectPay');
    Route::post ('process', 'Savitriya\Icici_upi\IciciUpiController@iciciUpiCallback');
    Route::post ('transaction-status', 'Savitriya\Icici_upi\IciciUpiController@transacrtionStatus');
    //Route::get ('transaction-status', 'Savitriya\Icici_upi\IciciUpiController@transacrtionStatus');
});
