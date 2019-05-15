<?php 



/**
  * 
  */
 class Route
 {

 	private $path;
 	private $callback;
 	private $headers;
 	private $method;

 	private $request;
 	
 	function __construct(String $path,$callback,String $headers='text/html',String $method='GET')
 	{
 		$this->path = $path;
 		$this->callback = $callback;
 		$this->headers = $headers;
 		$this->method = $method;
 	}

 	/**
 	 * Returns the callback registered for the route
 	 * @return function
 	 */
 	public function callback()
 	{
		header("Content-Type: ".$this->headers, true);

		if (strpos($this->callback, '@') ) {
			$callback = explode('@', $this->callback);
			$method = $callback[1];

			$instance = $callback[0]::getInstance();

			if (method_exists($instance, $method)) {
				
	        	return $instance->{$method}($this->request);
			}
		}else{
			//If view
			echo $this->callback;
		}
 	}


 	public function setPath($path)
 	{
 		$this->path = $path;
 	}

 	/**
 	 * Returns true if a Request matches this Route
 	 * @param  Request
 	 * @return boolean
 	 */
 	public function matches(Request $req){
 		if ($this->path == $req->getPath() && $this->method == $req->getMethod()) {
 			$this->setRequest($req);
 			return true;
 		}else{
 			return false;
 		}
 	}

 	/**
 	 * Sets the request
 	 * @param Request
 	 */
 	public function setRequest(Request $req)
 	{
 		$this->request = $req;
 	}
 } 

 ?>