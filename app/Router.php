<?php 

/**
 * 
 */
class Router
{
	private static $instance;
	private $routes = [];

	
	private function __construct()
	{
		//Register App routes
		$this->routes[] = new Route('/',file_get_contents('../public/app.html'));
		$this->routes[] = new Route('/api','Api@get','application/json');
		$this->routes[] = new Route('/api','Api@store','application/json','POST');
		$this->routes[] = new Route('/api','Api@update','application/json','PUT');
		$this->routes[] = new Route('/api/cleardb','Api@clearDB','application/json','POST');
		$this->routes[] = new Route('/api','Api@delete','application/json','DELETE');
	}

	public static function getInstance()
    {
        if(!self::$instance)
        {
            self::$instance = new self;
        }        
        return self::$instance;
    }

    //Facade for getting the route
	public static function getRoute(Request $req): Route
	{
		return self::getInstance()->get($req);
	}

	/**
	 * Returns the corresponding Route according to Request
	 * @param  Request
	 * @return Route
	 */
	public function get(Request $req): Route
	{
		foreach ($this->routes as $route) {
			if ($route->matches($req)) {
				return $route;
			}
		}
		return new Route('','Page not found :(');
	}
}

?>