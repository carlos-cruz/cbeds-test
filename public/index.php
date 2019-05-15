<?php

include '../app/DB.class.php';
include '../app/Interval.php';
include '../app/Request.php';
include '../app/Route.php';
include '../app/Router.php';
include '../app/Api.php';

include '../app/App.php';


$app = App::getInstance();

echo $app->run();

?>