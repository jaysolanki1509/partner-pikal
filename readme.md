##Settings Information
#Settings Names with default Value T-True F-False
1. feedbackPrint[F]            2. kotPrint[T]
3. processbillPrint[T]         4. billPrint[F]
5. multipleKotPrint[T]         6. mobileMandatory[F]
7. duplicateKotPrint[F]        8. orderNoReset[T]
9. incrementOrderNo[T]         10.bypassProcessBill[F]
11.invoiceDate[T]              12.displayNoOfPerson[T]
13.discountAfterTax[F]         14.skipBillPrint[F]
15.skipKotPrint[F]             16.beepOnKot[F]
17.overwriteDeliveryCharge[F]  18.itemWiseDiscount[F]
19.isRetailMode[F]             20.enterToSubmit[F]
21.itemWiseTax[F]              22.isSelfServiceMode[F]
23.closeCounterAutoCalc[F]     24.waitingList[F]
25.deleteYesterdayOrders[F]

##Tax Calculation functions
[Tax Model: Tax Calculation] => calcTaxes($amount,$taxname) returns $tax_total
[Tax Model: Tax List] => getTaxList($outlet_id) returns $tax_slot_array
[Utils Model: Tax Calculation] => taxCalculate($outlet_id, $price, $tax_name) returns encoded string and total tax
[Utils Model: Discount Calculation] => discountCalculate($price, $discount_type, $discount_value) returns final price


========================================================================================================================
## Laravel PHP Framework

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)
[![Total Downloads](https://poser.pugx.org/laravel/framework/downloads.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/framework/v/unstable.svg)](https://packagist.org/packages/laravel/framework)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as authentication, routing, sessions, queueing, and caching.

Laravel is accessible, yet powerful, providing powerful tools needed for large, robust applications. A superb inversion of control container, expressive migration system, and tightly integrated unit testing support give you the tools you need to build any application with which you are tasked.

## Official Documentation

Documentation for the framework can be found on the [Laravel website](http://laravel.com/docs).

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](http://laravel.com/docs/contributions).

### License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
