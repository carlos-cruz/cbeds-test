<?php 

/**
 * Database class
 * 
 */

class DB
{
	private $connection;
	private static $_instance;

	function __construct()
	{
		try{
			$config = parse_ini_file('../dbconfig.ini');
			$this->connection = new PDO('mysql:host='.$config["host"].';dbname='.$config["database"], $config['user'], $config['password']);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	public static function getInstance(){
		if (!self::$_instance) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Runs a query and fetches results
	 * @param  String
	 * @return Array
	 */
	function query(String $q): Array
	{
		$stmt = $this->connection->query($q);
		$stmt->setFetchMode(PDO::FETCH_ASSOC); 
		$result = $stmt->fetchAll();

		return $result;
	}

	/**
	 * Returns a collection of Intervals
	 * @param  String
	 * @param  Array|array
	 * @return [type]
	 */
	function getCollection(String $query,Array $values=[]){
		try{
			$stmt = $this->connection->prepare($query);
			$stmt->execute($values);
			$stmt->setFetchMode(PDO::FETCH_ASSOC); 
			$results = $stmt->fetchAll();
			
			
	        $collection = [];

			foreach ($results as $key => $value) {
				$collection[] = new Interval($value);
			}


			return $collection;

		}catch(PDOException $e){
			echo 'Error: '.$e->getMessage();
		}
	}

	/**
	 * Executes an SQL statement and returns lastInsertId if exists
	 * @param  String
	 * @param  Array|array
	 * @return [type]
	 */
	function execute(String $query,Array $values=[])
	{
		try{
			$stmt = $this->connection->prepare($query);
			$stmt->execute($values);

			return $this->connection->lastInsertId();
			
		}catch(PDOException $e){
			echo 'Error: '.$e->getMessage();
		}
	}

	/**
	 * Clears the prices table
	 * @return [type]
	 */
	public function clearDb(){
		$this->execute("TRUNCATE `prices`");
	}
}

?>