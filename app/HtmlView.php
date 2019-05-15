<?php 

/**
* 
*/
class HtmlView extends ViewAbstract
{
	private $file;
	
	function __construct(String $htmlFile, int $code = 200)
	{
		$this->file = $htmlFile;
		parent::__construct($code);
	}

	public function getContent(): string
	{
		return file_get_contents($this->file);
	}
}

?>