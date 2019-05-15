<?php 

/**
* 
*/
class JsonView extends ViewAbstract
{
	private $content;
	
	function __construct(Array $content, int $code = 200)
	{
		$this->content = $content;
		parent::__construct($code);
	}

	public function getContent(): string
	{
		return json_encode($this->content);
	}
}

?>