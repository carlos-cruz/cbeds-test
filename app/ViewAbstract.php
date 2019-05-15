<?php 

/**
* 
*/

abstract Class ViewAbstract {

	private $code;

	function __construct(int $code){
		$this->code = $code;
	}

	public function getCode(): int
	{
		return $this->code;
	}

	abstract function getContent(): string;

}


?>