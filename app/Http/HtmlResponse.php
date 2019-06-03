<?php 
namespace App\Http;

use App\Views\HtmlView;

/**
* 
*/
class HtmlResponse extends Response
{	
	function __construct(HtmlView $view,int $status)
	{
		parent::__construct($status,['Content-type' => 'text/html'],$view->getContent());
	}
}

?>