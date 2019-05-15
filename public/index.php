<?php

include '../app/DB.class.php';
include '../app/Interval.php';

include '../app/Response.php';
include '../app/JsonResponse.php';
include '../app/HtmlResponse.php';
include '../app/ViewAbstract.php';
include '../app/JsonView.php';
include '../app/HtmlView.php';

include '../app/Request.php';
include '../app/Route.php';
include '../app/Router.php';
include '../app/RestfullControllerInterface.php';
include '../app/Api.php';


include '../app/App.php';


$app = App::getInstance();

$app->run();

?>