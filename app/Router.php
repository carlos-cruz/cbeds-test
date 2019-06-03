<?php 

namespace App;

use App\Route;
use App\Request;

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
		$this->routes[] = new Route('/api','App\Api@get');
		$this->routes[] = new Route('/api','App\Api@store','POST');
		$this->routes[] = new Route('/api','App\Api@update','PUT');
		$this->routes[] = new Route('/api/cleardb','App\Api@clearDB','POST');
		$this->routes[] = new Route('/api','App\Api@delete','DELETE');
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
		return new Route('','../views/404.html');
	}
}

?>