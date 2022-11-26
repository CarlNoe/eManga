<?php

use Framework\Request\Request;
use Framework\Routing;

require '../vendor/autoload.php';

//call the routing class

//call the request class
$request = Request::fromGlobals();
//call the route method
Routing\Routing::init()->route($request);
