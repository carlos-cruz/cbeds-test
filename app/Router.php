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
		$this->routes[] = new Route('/','../public/app.html');
		$this->routes[] = new Route('/api','Api@get',['Content-Type' => 'application/json']);
		$this->routes[] = new Route('/api','Api@store',['Content-Type' => 'application/json'],'POST');
		$this->routes[] = new Route('/api','Api@update',['Content-Type' => 'application/json'],'PUT');
		$this->routes[] = new Route('/api/cleardb','Api@clearDB',['Content-Type' => 'application/json'],'POST');
		$this->routes[] = new Route('/api','Api@delete',['Content-Type' => 'application/json'],'DELETE');
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