<?php 


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