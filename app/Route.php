<?php 
namespace App;

use App\Request;
use App\Views\ViewAbstract;
use App\Views\HtmlView;

/**
  * 
  */
 class Route
 {

 	private $path;
 	private $callback;
 	private $method;

 	private $request;
 	
 	function __construct(String $path,String $callback,String $method='GET')
 	{
 		$this->path = $path;
 		$this->callback = $callback;
 		$this->method = $method;
 	}

 	/**
 	 * Returns the callback registered for the route
 	 * @return function
 	 */
 	public function callback(): ViewAbstract
 	{
		if (strpos($this->callback, '@') ) {
			$callback = explode('@', $this->callback);
			$method = $callback[1];

			$instance = $callback[0]::getInstance();

			if (method_exists($instance, $method)) {
				
	        	return $instance->{$method}($this->request);
			}
		}else{
			//If view
			return new HtmlView($this->callback);
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
 			//print($this->path.' - ' . $req->getMethod());die();

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