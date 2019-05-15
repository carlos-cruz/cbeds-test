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
	 */
	public function run()
	{
		$request = $this->getRequest();
		$route = Router::getRoute($request);

		//Execute Route callback to get a View
		$view = $route->callback();

		//Return a response
		$response = $this->prepareResponse($view);
		$response->output();
	}

	
	/**
	 * Gets the Request Object
	 * @return Request
	 */
	public function getRequest(): Request
	{
		//Get php://input for PUT and DELETE methods
		parse_str(file_get_contents('php://input'), $_DATA);
		return new Request($_SERVER, $_GET + $_POST + $_DATA);
	}

	/**
	 * Prepares the Response depending on the received View
	 * @param  View   $view
	 * @return Response
	 */
	public function prepareResponse(ViewAbstract $view): Response
	{
		if ($view instanceof  JsonView) {
			return new JsonResponse($view,$view->getCode());
		}else if ($view instanceof  HtmlView) {
			return new HtmlResponse($view,$view->getCode());
		}
	}

}

?>