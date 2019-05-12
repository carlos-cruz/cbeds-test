<?php 


/**
 * 
 */
class App
{
	private $db;	

	private static $instance;

	private $routes = [];

	
	function __construct()
	{
		$this->db = DB::getInstance();

		//Register App routes
		$this->routes[] = new Route('/',file_get_contents('../public/app.html'));
		$this->routes[] = new Route('/api','Api@get','application/json');
		$this->routes[] = new Route('/api/new','Api@create','application/json','POST');
		$this->routes[] = new Route('/api/update','Api@update','application/json','POST');
		$this->routes[] = new Route('/api/cleardb','Api@clearDB','application/json','POST');
		$this->routes[] = new Route('/api/delete','Api@delete','application/json','POST');
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
		$route = $this->getRoute($request);
		//Execute Route callback and return the results
		return $route->callback();
	}

	/**
	 * Returns the corresponding Route according to Request
	 * @param  Request
	 * @return Route
	 */
	public function getRoute(Request $req): Route
	{
		foreach ($this->routes as $route) {
			if ($route->matches($req)) {
				return $route;
			}
		}
		return new Route('','Page not found :(');
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