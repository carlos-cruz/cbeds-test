<?php 


/**
 * 
 */
class Response
{
	private $code;
	private $headers;
	private $body;
	
	function __construct(int $code, Array $headers, String $body)
	{
		$this->code = $code;
		$this->headers = $headers;
		$this->body = $body;
	}

	public function output(){
		http_response_code($this->code);
		foreach($this->headers as $key => $value)
        {
            header($key.': '.$value);
        }
		echo $this->body;
	}
}


?>