<?php 
namespace App\Http;

use App\Views\JsonView;

/**
* 
*/
class JsonResponse extends Response
{	
	function __construct(JsonView $view,int $status)
	{
		parent::__construct($status,['Content-type' => 'application/json'],$view->getContent());
	}
}

?>