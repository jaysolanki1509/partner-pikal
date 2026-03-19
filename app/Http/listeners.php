<?php
/**
 * Created by PhpStorm.
 * User: rubin
 * Date: 5/15/2015
 * Time: 3:16 PM
 */

Event::listen(OrderEventHandler::EVENT, 'OrderEventHandler');