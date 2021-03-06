<?php 

namespace App;

/**
 * 
 */
class Request
{
	
	private $method;
	private $path;
	private $data = [];

	function __construct(Array $info, Array $data)
	{
		if (isset($info['REQUEST_METHOD'])) {
			$this->method = $info['REQUEST_METHOD'];
		}

		if (isset($data)) {
			$this->data = $data;
		}

		if (isset($info['PATH_INFO'])) {
			$this->path = $info['PATH_INFO'];
		}else if(isset($info['REQUEST_URI'])){
			$this->path = $info['REQUEST_URI'];
		}else{
			$this->path = "/";
		}
	}

	public function getMethod()
	{
		return $this->method;
	}

	public function getPath()
	{
		return $this->path;
	}

	/**
	 * Returns a value from the request
	 * @param  String
	 * @return [type]
	 */
	public function data(String $field)
	{
		if ($field != null && isset($this->data[$field])) {
			return $this->data[$field];
		}
	}

}

?>