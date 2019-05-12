<?php 

/**
 * 
 */
class Request
{
	
	private $method;
	private $path;
	private $post = [];

	function __construct(Array $info)
	{
		if (isset($info['REQUEST_METHOD'])) {
			$this->method = $info['REQUEST_METHOD'];
		}

		if (isset($_POST)) {
			$this->post = $_POST;
		}

		if (isset($info['PATH_INFO'])) {
			$this->path = $info['PATH_INFO'];
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
	 * Returns a post field value from the request
	 * @param  String
	 * @return [type]
	 */
	public function post(String $field)
	{
		if ($field != null && isset($this->post[$field])) {
			return $this->post[$field];
		}
	}

}

?>