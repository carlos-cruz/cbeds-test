<?php 


/**
 * 
 */
class App
{
	private $db;	

	private static $instance;

	
	private function __construct()
	{
		$this->db = DB::getInstance();
	}

	public static function getInstance(){
		if (!self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Run the Application
	 * @return [type]
	 */
	public function run()
	{
		$request = $this->getRequest();
		$route = Router::getRoute($request);
		//Execute Route callback and return the results
		return $route->callback();
	}

	
	/**
	 * Gets the Request Object
	 * @return Request
	 */
	public function getRequest(): Request
	{
		return new Request($_SERVER);
	}

}

?>